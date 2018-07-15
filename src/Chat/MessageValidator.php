<?php

namespace App\Chat;


use JsonSchema\Constraints\Constraint;

class MessageValidator
{
    /**
     * @var \JsonSchema\Validator
     */
    protected $validator;

    /**
     * @var object JSON Schema to to validate the message.
     */
    protected $jsonSchema;

    /**
     * MessageValidator constructor.
     *
     * @param \JsonSchema\Validator $validator
     */
    public function __construct(\JsonSchema\Validator $validator)
    {
        $this->validator = $validator;

        $this->jsonSchema = (object)[
            "type" => "object",
            "properties" => (object)[
                "recipients" => (object)[
                    "type" => "array",
                    "items" => (object)[
                        "type" => "integer",
                    ],
                ],
                "body" => (object)[
                    "type" => "string",
                ],
            ],
        ];
    }

    /**
     * Validate message previously converted to object with json_decode.
     *
     * @param \stdClass $message
     *
     * @return bool
     */
    public function validate(\stdClass $message): bool
    {
        $this->validator->validate(
            $message, $this->jsonSchema,
            Constraint::CHECK_MODE_COERCE_TYPES
        );

        return $this->validator->isValid();
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function validateString(string $message): bool
    {
        $json = json_decode($message);
        return $this->validate($json);
    }
}
