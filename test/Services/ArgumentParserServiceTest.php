<?php

namespace Tests\Services;

use App\Enums\User;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Services\ArgumentParserService;
use PHPUnit\Framework\TestCase;

class ArgumentParserServiceTest extends TestCase
{
    private ArgumentParserService $argumentParserService;

    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        ArgumentParserService $argumentParserService = null,
    )
    {
        $this->argumentParserService =  $argumentParserService ?? new ArgumentParserService();
        parent::__construct($name, $data, $dataName);
    }


    public function argumentProvider():iterable
    {
        return
            [
                [123, User::FIRST_NAME->value],
                [User::LAST_NAME->value, User::LAST_NAME->value],
                [User::EMAIL->value, User::EMAIL->value],
            ];
    }

    /**
     * @dataProvider argumentProvider
     */
    public function testItConvertArgumentToString(
        string|int $inputValue,
        string|int $expectedValue,

    )
    {
        $argument = $this->argumentParserService->parseRawInput(
            [
                sprintf('%s=%s', User::FIRST_NAME->value, $inputValue),
            ]
        );

        $firstName = $argument->get(User::FIRST_NAME->value);
        $this->assertEquals($expectedValue, $firstName);
    }

    /**
     * @throws ArgumentException
     * @throws CommandException
     */
    public function testItReturnArgumentValueVyName()
    {
        $argument = $this->argumentParserService->parseRawInput(
            [
                sprintf('%s=123', User::FIRST_NAME->value),
                sprintf('%s=Fadeev', User::LAST_NAME->value),
                sprintf('%s=fadeev@start2play.ru', User::EMAIL->value)
            ]
        );

        $firstName = $argument->get(User::FIRST_NAME->value);
        $this->assertEquals(123, $firstName);
    }

    public function testItThrowsAnExceptionWhenInputArgumentInvalid(): void
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage("Параметры должны быть в формате fieldName=fieldValue");

        $this->argumentParserService->parseRawInput(
            [
                sprintf('%s', User::FIRST_NAME->value),
            ],
        );
    }

}