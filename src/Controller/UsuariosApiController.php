<?php

namespace App\Controller;

use App\Form\UsuarioFormType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuariosApiController extends AbstractController
{    
    private $em;
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository, EntityManagerInterface $em){
        $this->usuarioRepository = $usuarioRepository;
        $this->em = $em;
    }

    public function listaUsuarios(): JsonResponse
    {
        $usuarios = $this->usuarioRepository->findAll();
        
        foreach($usuarios as $usuario){
            $res[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'apellido' => $usuario->getApellido(),
                'fechaNacimiento' => $usuario->getFechaNacimiento()->format('Y-m-d'),
            ];
        }

        return $this->json($res);
    }

    public function addUsuario(Request $request){
        $json = json_decode($request->getContent(), true);
        $form = $this->createForm(UsuarioFormType::class);
        $form->submit($json);

        if(!$form->isValid()){
            //throw error (like printing $form->getErrors(true));
        }
         
        $newUsuario = $form->getData();

        $this->em->persist($newUsuario);
        $this->em->flush();

        return new Response('', 204);
    } 
}
