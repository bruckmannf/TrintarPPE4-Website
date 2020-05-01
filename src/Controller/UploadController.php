<?php

namespace App\Controller;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Licence;
use App\Entity\Magasin;
use App\Entity\optionMagasin;
use App\Entity\Produit;
use App\Entity\Sexe;
use App\Entity\Utilisateur;
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
            return $this->redirectToRoute('admin.licence.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadLicenceJSON", name="upload.licenceJSON")
     */
    public function uploadLicence(Request $request)
    {
        $form = $this->createForm(UploadJSONType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadJSON')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'licenceImportJSON.json');
                    $importJSON = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/licenceImportJSON.json", true);
                    $lesClients = json_decode($importJSON, true);
                    foreach ($lesClients as $v){
                        $licence = new Licence();
                        $licence->setLibelle($v['libelle']);
                        $this->em->persist($licence);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.licence.index');
        }
        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadLicenceXML", name="upload.licenceXML")
     */
    public function uploadLicenceXML(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadXML')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'licenceImportXML.xml');
                    $lesClients = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/licenceImportXML.xml");
                    foreach ($lesClients as $v){
                        $licence = new Licence();
                        $licence->setLibelle($v->libelle);
                        $this->em->persist($licence);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.licence.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadAuteurJSON", name="upload.auteurJSON")
     */
    public function uploadAuteur(Request $request)
    {
        $form = $this->createForm(UploadJSONType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadJSON')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'auteurImportJSON.json');
                    $importJSON = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/auteurImportJSON.json", true);
                    $lesClients = json_decode($importJSON, true);
                    foreach ($lesClients as $v){
                        $auteur = new Auteur();
                        $auteur->setLibelle($v['libelle']);
                        $this->em->persist($auteur);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.auteur.index');
        }
        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadAuteurXML", name="upload.auteurXML")
     */
    public function uploadAuteurXML(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadXML')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'auteurImportXML.xml');
                    $lesClients = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/auteurImportXML.xml");
                    foreach ($lesClients as $v){
                        $auteur = new Auteur();
                        $auteur->setLibelle($v->libelle);
                        $this->em->persist($auteur);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.auteur.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadOptionJSON", name="upload.optionJSON")
     */
    public function uploadOption(Request $request)
    {
        $form = $this->createForm(UploadJSONType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadJSON')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'optionImportJSON.json');
                    $importJSON = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/optionImportJSON.json", true);
                    $lesClients = json_decode($importJSON, true);
                    foreach ($lesClients as $v){
                        $option = new optionMagasin();
                        $option->setLibelle($v['libelle']);
                        $this->em->persist($option);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.option.index');
        }
        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadOptionXML", name="upload.optionXML")
     */
    public function uploadOptionXML(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadXML')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'optionImportXML.xml');
                    $lesClients = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/optionImportXML.xml");
                    foreach ($lesClients as $v){
                        $option = new optionMagasin();
                        $option->setLibelle($v->libelle);
                        $this->em->persist($option);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.option.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadSexeJSON", name="upload.sexeJSON")
     */
    public function sexeUpload(Request $request)
    {
        $form = $this->createForm(UploadJSONType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadJSON')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'sexeImportJSON.json');
                    $importJSON = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/sexeImportJSON.json", true);
                    $lesClients = json_decode($importJSON, true);
                    foreach ($lesClients as $v){
                        $sexe = new Sexe();
                        $sexe->setLibelle($v['libelle']);
                        $this->em->persist($sexe);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.sexe.index');
        }
        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadSexeXML", name="upload.sexeXML")
     */
    public function sexeUploadXML(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadXML')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'sexeImportXML.xml');
                    $lesClients = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/sexeImportXML.xml");
                    foreach ($lesClients as $v){
                        $sexe = new Sexe();
                        $sexe->setLibelle($v->libelle);
                        $this->em->persist($sexe);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.sexe.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadUserJSON", name="upload.userJSON")
     */
    public function userUpload(Request $request)
    {
        $form = $this->createForm(UploadJSONType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadJSON')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'userImportJSON.json');
                    $importJSON = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/userImportJSON.json", true);
                    $lesClients = json_decode($importJSON, true);
                    foreach ($lesClients as $v){
                        $user = new Utilisateur();
                        $user->setNom($v['nom']);
                        $user->setPrenom($v['prenom']);
                        $user->setDescription($v['description']);
                        $user->setEmail($v['email']);
                        $user->setLat($v['lat']);
                        $user->setLng($v['lng']);
                        $user->setTelephone($v['telephone']);
                        $user->setPassword($v['password']);
                        $user->setAdresse($v['adresse']);
                        $user->setCodePostal($v['codePostal']);
                        $user->setConfirmationToken($v['confirmationToken']);
                        $user->setEnabled($v['enabled']);
                        $this->em->persist($user);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.utilisateur.index');
        }
        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadUserXML", name="upload.userXML")
     */
    public function uploadUserXML(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadXML')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'userImportXML.xml');
                    $lesClients = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/userImportXML.xml");
                    foreach ($lesClients as $v){
                        $user = new Utilisateur();
                        $user->setNom($v->nom);
                        $user->setPrenom($v->prenom);
                        $user->setDescription($v->description);
                        $user->setEmail($v->email);
                        $user->setLat($v->lat);
                        $user->setLng($v->lng);
                        $user->setTelephone($v->telephone);
                        $user->setPassword($v->password);
                        $user->setAdresse($v->adresse);
                        $user->setCodePostal($v->codePostal);
                        $user->setConfirmationToken($v->confirmationToken);
                        $user->setEnabled($v->enabled);
                        $this->em->persist($user);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.utilisateur.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadMagasinJSON", name="upload.magasinJSON")
     */
    public function magasinUpload(Request $request)
    {
        $form = $this->createForm(UploadJSONType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadJSON')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'magasinImportJSON.json');
                    $importJSON = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/magasinImportJSON.json", true);
                    $lesClients = json_decode($importJSON, true);
                    foreach ($lesClients as $v){
                        $magasin = new Magasin();
                        $magasin->setLat($v['lat']);
                        $magasin->setLng($v['lng']);
                        $magasin->setNom($v['nom']);
                        $magasin->setTelephone($v['telephone']);
                        $magasin->setCourriel($v['courriel']);
                        $magasin->setHoraireOuverture($v['horaireOuverture']);
                        $magasin->setAdresse($v['adresse']);
                        $magasin->setCodePostal($v['codePostal']);
                        $this->em->persist($magasin);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.magasin.index');
        }
        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadMagasinXML", name="upload.magasinXML")
     */
    public function uploadMagasinXML(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadXML')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'magasinImportXML.xml');
                    $lesClients = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/magasinImportXML.xml");
                    foreach ($lesClients as $v){
                        $magasin = new Magasin();
                        $magasin->setHoraireOuverture($v->horaireOuverture);
                        $magasin->setAdresse($v->adresse);
                        $magasin->setLng($v->lng);
                        $magasin->setLat($v->lat);
                        $magasin->setCodePostal($v->codePostal);
                        $magasin->setNom($v->nom);
                        $magasin->setTelephone($v->telephone);
                        $magasin->setCourriel($v->courriel);
                        $this->em->persist($magasin);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.magasin.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadProduitJSON", name="upload.produitJSON")
     */
    public function produitUpload(Request $request)
    {
        $form = $this->createForm(UploadJSONType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadJSON')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'produitImportJSON.json');
                    $importJSON = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/produitImportJSON.json", true);
                    $lesClients = json_decode($importJSON, true);
                    foreach ($lesClients as $v){
                        $produit = new Produit();
                        $produit->setLibelle($v['libelle']);
                        $produit->setPrixht($v['prixht']);
                        $produit->setStock($v['stock']);
                        $produit->setDescription($v['description']);
                        $produit->setSynopsis($v['synopsis']);
                        $this->em->persist($produit);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.produit.index');
        }
        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadProduitXML", name="upload.produitXML")
     */
    public function uploadProduitXML(Request $request)
    {
        $form = $this->createForm(UploadXMLType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $upload = $form->get('uploadXML')->getData();
            if ($upload) {
                try {
                    $upload->move('ress', 'produitImportXML.xml');
                    $lesClients = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/webMarchandSymfony/public/ress/produitImportXML.xml");
                    foreach ($lesClients as $v){
                        $produit = new Produit();
                        $produit->setLibelle($v->libelle);
                        $produit->setPrixht($v->prixht);
                        $produit->setStock($v->stock);
                        $produit->setDescription($v->description);
                        $produit->setSynopsis($v->synopsis);
                        $this->em->persist($produit);
                        $this->em->flush();
                    }
                } catch (FileException $ex) {
                    dump($ex);
                }
            }
            return $this->redirectToRoute('admin.produit.index');
        }
        return $this->render('upload/indexXML.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
