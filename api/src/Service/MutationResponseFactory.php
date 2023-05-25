<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MutationResponseFactory
{
    private int $code;

    private string $message;

    public function success(): self
    {
        $this->code = Response::HTTP_OK;
        $this->message = 'success';

        return $this;
    }

    public function failure(): self
    {
        $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
        $this->message = 'failure';

        return $this;
    }

    public function violations(ConstraintViolationListInterface $violations): self
    {
        $this->code = Response::HTTP_BAD_REQUEST;
        $this->message = (string) $violations[0]?->getMessage();

        return $this;
    }

    public function getResponse(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}
