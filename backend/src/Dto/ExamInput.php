<?php
namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class ExamInput
{   
    #[Assert\NotNull]
    public int $student;

    #[Assert\NotBlank]
    public string $location;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $date;

    #[Assert\NotBlank]
    #[Assert\Time]
    public string $time;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['Confirmé', 'À organiser', 'Annulé', 'En recherche de place'])]
    public string $status;
}
