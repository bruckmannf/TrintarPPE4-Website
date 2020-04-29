<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Form\UploadJSONType;
use App\Form\UploadXMLType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @var CategorieRepository
     */

    private $Crepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, CategorieRepository $Crepository)
    {
        $this->Crepository = $Crepository;
        $this->em = $em;
    }

    /**
     * @Route("/uploadCategorieJSON", name="upload.categorieJSON")
     */
    public function uploadCategorie(Request $request)
    {
        $form = $this->createForm(UploadJSONType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadJSON')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'categorieImportJSON.json');
                    $importJSON = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/categorieImportJSON.json", true);
                    $lesClients = json_decode($importJSON, true);
                    foreach ($lesClients as $v){
                        $categorie = new Categorie();
                        $categorie->setLibelle($v['libelle']);
                        $this->em->persist($categorie);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.categorie.index');
        }
        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadCategorieXML", name="upload.categorieXML")
     */
    public function uploadCategorieXML(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadXML')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'categorieImportXML.xml');
                    $lesClients = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/categorieImportXML.xml");
                    foreach ($lesClients as $v){
                        $categorie = new Categorie();
                        $categorie->setLibelle($v->libelle);
                        $this->em->persist($categorie);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.categorie.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
