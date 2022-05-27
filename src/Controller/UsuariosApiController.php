<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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
}
