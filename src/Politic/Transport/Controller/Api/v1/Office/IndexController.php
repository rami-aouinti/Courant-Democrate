<?php

declare(strict_types=1);

namespace App\Politic\Transport\Controller\Api\v1\Office;

use App\General\Domain\Utils\JSON;
use App\Politic\Domain\Entity\Office;
use App\Quiz\Domain\Entity\Quiz;
use App\Quiz\Domain\Entity\Score;
use App\User\Domain\Entity\User;
use App\User\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class IndexController
 *
 * @OA\Tag(name="Office Application")
 *
 * @package App\Office
 */
#[AsController]
readonly class IndexController
{

    #[Route(
        path: '/v1/office/add',
        methods: [Request::METHOD_POST],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function addOffice(Request $request, EntityManagerInterface $em, User $loggedInUser, UserRepository $userRepository): JsonResponse
    {

        $office = new Office();
        $all = $request->request->all();
        $users = $all['users'];
        foreach ($users as $user) {
            $userObject = $userRepository->findOneBy([
                'username' => $user['username']
            ]);
            $office->addUser($userObject);
        }


        $office->setOfficeName($request->request->get('officeName'));
        $office->setOfficeDescription($request->request->get('officeDescription'));


        $em->persist($office);
        $em->flush();
        return new JsonResponse(array('name' => $office->getOfficeName()));
    }


    /**
     * Get current user profile data, accessible only for 'IS_AUTHENTICATED_FULLY' users.
     *
     * @OA\Response(
     *     response=200,
     *     description="Quiz data",
     *     @OA\JsonContent(
     *         ref=@Model(
     *             type=Quiz::class,
     *             groups={"set.UserQuiz"},
     *         ),
     *     ),
     *  )
     * @OA\Response(
     *     response=401,
     *     description="Invalid token (not found or expired)",
     *     @OA\JsonContent(
     *         type="object",
     *         example={"code": 401, "message": "JWT Token not found"},
     *         @OA\Property(property="code", type="integer", description="Error code"),
     *         @OA\Property(property="message", type="string", description="Error description"),
     *     ),
     * )
     *
     * @throws JsonException
     */
    #[Route(
        path: '/v1/application/questions/{category}',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function getQuestions(ManagerRegistry $entityManager, SerializerInterface $serializer, string $category): JsonResponse
    {
        $qb = $entityManager->getManager()->createQueryBuilder();
        $qb
            ->select('q')
            ->from('App\Quiz\Domain\Entity\Question', 'q')
        ;

        $questions = $qb->getQuery()->getResult();

        $questionArray = [];
        foreach ($questions as $key => $question) {
            $answers = $question->getAnswers();
            $answersArray = [];
            foreach ($answers as $index => $answer) {
                $answersArray[$index]['answerText'] = $answer->getText();
                $answersArray[$index]['isCorrect'] = $answer->getCorrect();

            }


            $categoryExist = false;
            $categories = $question->getCategories();


            foreach ($categories as $categoryItem) {
                if($categoryItem->getId() === $category) {
                    $categoryExist = true;
                }

            }

            if ($categoryExist) {
                $questionArray[$key]['id'] = $question->getId();
                $questionArray[$key]['questionText'] = $question->getText();
                $questionArray[$key]['max_duration'] = $question->getMaxDuration();
                $questionArray[$key]['complicated'] = $question->getComplicated();
                $questionArray[$key]['categories'] = $question->getCategories();
                $questionArray[$key]['answerOptions'] = $answersArray;
                $questionArray[$key]['language'] = $question->getLanguage();
            }


        }

        $questionsArray = [];
        /*
        foreach($questions as $question)
        {
            //print_r($question);
            $questionArray = $question[0];
            $questionsArray[] = (array)$question[0];
            $questionsArray[]['answers'] = $question[0]->getAnswers();
            $questionsArray[]['categories'] = $question[0]->getCategories();
            $questionsArray[]['language'] = $question[0]->getLanguage();
        }
*/
        /** @var array<string, string|array<string, string>> $output */
        $output = JSON::decode(
            $serializer->serialize(
                $questionArray,
                'json',
                [
                    'groups' => Quiz::SET_USER_QUIZ,
                ]
            ),
            true,
        );

        return new JsonResponse($output);
    }
}
