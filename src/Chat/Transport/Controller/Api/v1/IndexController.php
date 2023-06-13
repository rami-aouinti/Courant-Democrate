<?php

namespace App\Chat\Transport\Controller\Api\v1;

use App\Chat\Domain\Entity\Message;
use App\Chat\Domain\Repository\ConversationRepository;
use App\Chat\Domain\Entity\Conversation;
use App\Chat\Domain\Entity\Participant;
use App\Chat\Domain\Repository\MessageRepository;
use App\Chat\Domain\Repository\ParticipantRepository;
use App\User\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\User\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;

/**
 * Class IndexController
 *
 * @OA\Tag(name="Chat Application")
 *
 * @package App\User
 */
#[AsController]
class IndexController extends AbstractController
{

    private ConversationRepository $conversationRepository;

    private readonly UserRepositoryInterface $userRepository;

    private readonly MessageRepository $messageRepository;

    private readonly ParticipantRepository $participantRepository;


    const ATTRIBUTES_TO_SERIALIZE = [
        'id',
        'content',
        'createdAt',
        'mine'
    ];

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param ConversationRepository $conversationRepository
     */


    /**
     * @var PublisherInterface
     */
    private $publisher;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ConversationRepository $conversationRepository,
        MessageRepository $messageRepository,
        ParticipantRepository $participantRepository,
    )
    {
        $this->conversationRepository = $conversationRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->participantRepository = $participantRepository;
    }



    /**
     * @Route("/", name="newConversations", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route(
        path: '/v1/newConversations',
        methods: [Request::METHOD_POST],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function index(Request $request)
    {
        $otherUser = $request->get('otherUser', 0);
        $otherUser = $this->userRepository->find($otherUser['id']);

        if(is_null($otherUser)){
            throw new \Exception("! The user was not found.");
        }

        if($otherUser->getId() === $this->getUser()->getUserIdentifier()) {
            throw new \Exception("! That's deep but you cannot create a conversation with yourself.");
        }

        $conversation = $this->conversationRepository->findOneConversationBetweenUsers(
            $otherUser->getId(),
            $this->getUser()->getUserIdentifier()
        );

        if(count($conversation)){
            throw new \Exception("! The conversation already exists.");
        }


        $conversation = new Conversation();

        $participant = new Participant();
        $participant->setConversation($conversation);

        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch(\Exception $exception){
            $this->entityManager->rollback();

            throw $exception;
        }

        return $this->json([
            'id' => $conversation->getId()
        ], Response::HTTP_CREATED, [], []);
    }

    #[Route(
        path: '/v1/conversations',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function getConversations(Request $request, HubInterface $hub): JsonResponse
    {
        $config = [
            'config' => [
                'topic' => 'chat',
            ]
        ];
        $conversations = $this->conversationRepository->findOneBySomeField($this->getUser()->getUserIdentifier());

        return $this->json([$conversations, $config]);
    }



    /**
     * @Route("/{id}", name="getMessages", methods={"GET"})
     * @param Request $request
     * @param Conversation $conversation
     * @return Response
     */
    #[Route(
        path: '/v1/messages/{id}',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function messages(
        Request $request,
        Conversation $conversation
    )
    {
        $messages = $this->messageRepository->findMessageByConversationId(
            $conversation->getId()
        );

        $conversation = $this->conversationRepository->findOneBySomeFieldByConversation($this->getUser()->getUserIdentifier() ,$conversation->getId());



        return $this->json(
            $conversation,
            Response::HTTP_OK,
            [],
            [
                'attributes' => self::ATTRIBUTES_TO_SERIALIZE
            ]
        );
    }

    /**
     * @Route("/{id}", name="newMessage", methods={"POST"})
     * @param Request $request
     * @param Conversation $conversation
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route(
        path: '/v1/newMessage/{id}',
        methods: [Request::METHOD_POST],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function newMessage(
        Request $request,
        Conversation $conversation,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $user = $this->userRepository->find($this->getUser()->getUserIdentifier());
        $convers = $this->conversationRepository->find($conversation->getId());
        $recipient = $this->participantRepository->findOneBySomeField(
            $convers,
            $user
        );


        $content = $request->get('content', null);

        $message = new Message();
        $message->setContent($content);

        $conversation->addMessage($message);
        $conversation->setLast($message);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($message);
            $this->entityManager->persist($conversation);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception){
            $this->entityManager->rollback();

            throw $exception;
        }

        $message->setMine(false);
        $messageSerialized = $serializer->serialize(
            $message,
            'json',
            [
                'attributes' => [
                    'id',
                    'content',
                    'createdAt',
                    'mine',
                    'conversation' => ['id']
                ]
            ]
        );

        return $this->json(
            $message,
            Response::HTTP_CREATED,
            [],
            [
                'attributes' => self::ATTRIBUTES_TO_SERIALIZE
            ]
        );
    }
}
