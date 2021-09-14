<?php

namespace Workana\Tests\Domain\Model\Issue;

use Exception;
use ReflectionClass;
use ReflectionException;
use Workana\Domain\Model\Issue\Issue;
use Workana\Tests\Domain\Model\Mother;

class IssueMother extends Mother
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function voting(): Issue
    {
        $reflectionClass = new ReflectionClass(Issue::class);

        /** @var Issue $issue */
        $issue = $reflectionClass->newInstanceWithoutConstructor();

        $data = [
            'status' => Issue::VOTING,
            'members' => [
                "John" => [
                    "status" => 'waiting',
                    "value" => 0
                ],
                "Pia" => [
                    "status" => 'voted',
                    "value" => 15
                ]
            ],
            'avg' => 7
        ];

        self::setProperties($reflectionClass, $issue, $data);

        return $issue;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function reveal(): Issue
    {
        $reflectionClass = new ReflectionClass(Issue::class);

        /** @var Issue $issue */
        $issue = $reflectionClass->newInstanceWithoutConstructor();

        $data = [
            'status' => Issue::REVEAL,
            'members' => [
                "John" => [
                    "status" => 'voted',
                    "value" => 10
                ],
                "Pia" => [
                    "status" => 'voted',
                    "value" => 5
                ]
            ],
            'avg' => 7
        ];

        self::setProperties($reflectionClass, $issue, $data);

        return $issue;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function passed(): Issue
    {
        $reflectionClass = new ReflectionClass(Issue::class);

        /** @var Issue $issue */
        $issue = $reflectionClass->newInstanceWithoutConstructor();

        $data = [
            'status' => Issue::REVEAL,
            'members' => [
                "John" => [
                    "status" => 'passed',
                    "value" => 0
                ],
                "Pia" => [
                    "status" => 'voted',
                    "value" => 10
                ]
            ],
            'avg' => 10
        ];

        self::setProperties($reflectionClass, $issue, $data);

        return $issue;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function random(): Issue
    {
        $reflectionClass = new ReflectionClass(Issue::class);

        /** @var Issue $issue */
        $issue = $reflectionClass->newInstanceWithoutConstructor();

        $johnVote = random_int(0, 100);
        $piaVote = random_int(0, 100);

        $data = [
            'status' => random_int(0, 1) === 0 ? Issue::VOTING : Issue::REVEAL,
            'members' => [
                "John" => [
                    "status" => match (random_int(0, 2)) {
                        0 => 'waiting',
                        1 => 'voted',
                        2 => 'passed'
                    },
                    "value" => $johnVote
                ],
                "Pia" => [
                    "status" => match (random_int(0, 2)) {
                        0 => 'waiting',
                        1 => 'voted',
                        2 => 'passed'
                    },
                    "value" => $piaVote
                ]
            ],
            'avg' => (int)(($johnVote + $piaVote) / 2)
        ];

        self::setProperties($reflectionClass, $issue, $data);

        return $issue;
    }
}
