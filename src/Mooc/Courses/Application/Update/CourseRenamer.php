<?php

declare(strict_types=1);

namespace CodelyTv\Mooc\Courses\Application\Update;

use CodelyTv\Mooc\Courses\Domain\CourseFinder;
use CodelyTv\Mooc\Courses\Domain\CourseName;
use CodelyTv\Mooc\Courses\Domain\CourseRepository;
use CodelyTv\Mooc\Shared\Domain\Courses\CourseId;
use CodelyTv\Shared\Domain\Bus\Event\EventBus;

final class CourseRenamer
{
    private CourseRepository $repository;
    private CourseFinder     $finder;
    private EventBus         $bus;

    public function __construct(CourseRepository $repository, EventBus $bus)
    {
        $this->repository = $repository;
        $this->finder     = new CourseFinder($repository);
        $this->bus        = $bus;
    }

    public function __invoke(CourseId $id, CourseName $newName): void
    {
        $course = $this->finder->__invoke($id);

        $course->rename($newName);

        $this->repository->save($course);
        $this->bus->publish(...$course->pullDomainEvents());
    }
}
