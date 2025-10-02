<?php
namespace App\Processor;

use ApiPlatform\State\ProcessorInterface;
use App\Dto\ExamInput;
use App\Entity\Exam;
use App\Repository\StudentRepository;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;

class ExamInputProcessor implements ProcessorInterface
{
    public function __construct(private StudentRepository $studentRepository, 
                                private EntityManagerInterface $em) {}

    /**
     * @param ExamInput|Exam $data
     * @param array $operation
     * @param array $context
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): Exam 
    {
        if ($data instanceof Exam) {
            return $data;
        }

        if (!$data instanceof ExamInput) {
            throw new \InvalidArgumentException('Expected ExamInput');
        }

        $exam = new Exam();

        $student = $this->studentRepository->find($data->student);

        if (!$student) {
            throw new \InvalidArgumentException("Aucun étudiant trouvé avec l'id ".$data->student);
        }

        $exam->setStudent($student);
        $exam->setLocation($data->location);
        $exam->setDate(new \DateTimeImmutable($data->date));
        $exam->setTime(new \DateTimeImmutable($data->time));
        $exam->setStatus($data->status);

        // Persistance
        $this->em->persist($exam);
        $this->em->flush();

        return $exam;
    }
}
