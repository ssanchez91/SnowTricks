<?php
/**
 * Created by PhpStorm.
 * User: sstee
 * Date: 17/10/2020
 * Time: 23:43
 */

namespace App\Service;


use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\FigureRepository;
use Symfony\Component\Security\Core\Security;

class CommentService
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var FigureRepository
     */
    private $figureRepository;
    /**
     * @var Security
     */
    private $security;

    public function __construct(CommentRepository $commentRepository, FigureRepository $figureRepository, Security $security)
    {
        $this->commentRepository = $commentRepository;
        $this->figureRepository = $figureRepository;
        $this->security = $security;
    }

    public function addComment($content_comment, $slug)
    {
        $comment = new Comment();
        $comment->setContent($content_comment);
        $comment->setFigure($this->figureRepository->findOneBy(['slug'=>$slug]));
        $comment->setUser($this->security->getUser());
        $comment->setCreatedAt(new \DateTime());

        return $this->commentRepository->addComment($comment);
    }
}