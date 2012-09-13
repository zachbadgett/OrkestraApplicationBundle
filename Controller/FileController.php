<?php

namespace Orkestra\Bundle\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * File controller
 *
 * @Route("/file")
 */
class FileController extends Controller
{
    /**
     * Outputs a file
     *
     * @Route("/{id}/download", name="download_file")
     * @Secure(roles="ROLE_USER")
     */
    public function downloadAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $file = $em->find('Orkestra\Bundle\ApplicationBundle\Entity\File', $id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to locate File');
        }

        $securityContext = $this->get('security.context');

        foreach ($file->getGroups() as $group) {
            if (!$securityContext->isGranted($group->getRole())) {
                throw $this->createNotFoundException('Unable to locate File');
            }
        }

        return new Response($file->getContent(), 200, array(
            'Content-Type' => $file->getMimeType(),
            'Content-Disposition' => sprintf('attachment; filename="%s"', $file->getFilename())
        ));
    }
}
