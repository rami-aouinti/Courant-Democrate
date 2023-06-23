<?php

declare(strict_types=1);

namespace App\Pdf\Application\Service;

use App\Article\Domain\Entity\Post;
use App\Article\Infrastructure\Repository\PostRepository;
use App\Pdf\Domain\Entity\Document;
use App\Pdf\Infrastructure\Repository\PdfRepository;
use App\User\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpKernel\KernelInterface;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function Symfony\Component\Translation\t;

/**
 * Class PdfService
 *
 * @package App\Pdf
 */
readonly class PdfService
{
    /**
     * @param PdfRepository $repository
     */
    public function __construct(
        private PdfRepository $repository,
        private EntityManagerInterface $entityManager,
        private Environment $templating,
        private KernelInterface $appKernel
    ) {
    }


    /**
     * @param User $loggedUser
     * @param $data
     * @return void
     */
    public function createDocument(User $loggedUser, $data): void
    {
        if ($data) {
            try {
                $this->savePdf();
            } catch (LoaderError|RuntimeError|FilterException|CrossReferenceException|SyntaxError|PdfTypeException|PdfParserException|PdfReaderException $e) {
                print_r($e->getCode());
            }
            $document = new Document();
            $document->setName($data['name']);
            $document->setPath($data['path']);
            $document->setUser($loggedUser);
            $document->setActive(true);
            $this->entityManager->persist($document);
            $this->entityManager->flush();
        }
    }

    /**
     * Method to check that "all" is ok within our application. This will try to do following:
     *  1) Remove data from database
     *  2) Create data to database
     *  3) Read data from database
     *
     * These steps should make sure that at least application database is working as expected.
     *
     * @throws Throwable
     */
    public function getDocument($slug): ?Document
    {
        return $this->repository->find($slug);
    }

    /**
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PdfTypeException
     * @throws CrossReferenceException
     * @throws PdfReaderException
     * @throws SyntaxError
     * @throws PdfParserException
     * @throws FilterException
     */
    private function savePdf(): void
    {
        $pdf = new \setasign\Fpdi\Tfpdf\Fpdi();
// add a page
        $pdf->AddPage();
        //print_r($_SERVER['DOCUMENT_ROOT']);
// set the source file
        $pdf->setSourceFile("test.pdf");
// import page 1
        $tplId = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
        $pdf->useTemplate($tplId, 10, 10, 100);


        $data = [
            'imageSrc'  => $this->imageToBase64($this->appKernel->getProjectDir() . '/public/rami.jpg'),
            'name'         => 'John Doe',
            'address'      => 'USA',
            'mobileNumber' => '000000000',
            'email'        => 'john.doe@email.com'
        ];
        $html =  $this->templating->render('pdf/index.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $output = $dompdf->output();
    }

    private function imageToBase64($path) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
