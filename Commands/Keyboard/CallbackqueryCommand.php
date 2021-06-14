<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class CallbackqueryCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Handle the callback query';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws \Exception
     */
    public function execute(): ServerResponse
    {
        $callback_query = $this->getCallbackQuery();
        $callback_data = $callback_query->getData();

        switch ($callback_data) {
            case ('allprojects'):
                $inline_keyboard = new InlineKeyboard(
                    [
                        new InlineKeyboardButton(['text' => 'Project 1', 'callback_data' => '/project-1']),
                        new InlineKeyboardButton(['text' => 'Project 2', 'callback_data' => '/project-2']),
                    ],
                    [
                        new InlineKeyboardButton(['text' => 'Project 3', 'callback_data' => '/project-3']),
                    ],
                );

                return Request::editMessageText(
                    [
                        'chat_id' => $callback_query->getMessage()->getChat()->getId(),
                        'message_id' => $callback_query->getMessage()->getMessageId(),
                        'text' => 'Выберите услугу из предложенных',
                        'reply_markup' => $inline_keyboard,
                    ]
                );

            case ('/project-1'):
                $text = 'Place your text about PROJECT 1 here';

                $dataArr = [
                    'text' => $text,
                    'tgUrl' => 'tg://resolve?domain=project-1',
                    'projectUrl' => 'https://project-1.com',
                ];
                return $this->answer($dataArr, $callback_query);

            case ('/project-2'):
                $text = 'Place your text about PROJECT 2 here';

                $dataArr = [
                    'text' => $text,
                    'tgUrl' => 'tg://resolve?domain=project-2',
                    'projectUrl' => 'https://project-2.com',
                ];
                return $this->answer($dataArr, $callback_query);

            case ('/project-3'):
                $text = 'Place your text about PROJECT 3 here';
                $dataArr = [
                    'text' => $text,
                    'tgUrl' => 'tg://resolve?domain=project-3',
                    'projectUrl' => 'https://project-3.com',
                ];
                return $this->answer($dataArr, $callback_query);
        }
    }

    private function answer(array $dataArr = [], $callback_query): ServerResponse
    {
        $inline_keyboard = new InlineKeyboard(
            [
                ['text' => 'Связаться в telegram', 'url' => $dataArr['tgUrl']],
                ['text' => 'Подать заявку на сайте', 'url' => $dataArr['projectUrl']],
            ],
            [
                ['text' => 'Показать все проекты', 'callback_data' => 'allprojects'],
            ],
        );

        return Request::editMessageText(
            [
                'chat_id' => $callback_query->getMessage()->getChat()->getId(),
                'message_id' => $callback_query->getMessage()->getMessageId(),
                'text' => $dataArr['text'],
                'parse_mode' => 'HTML',
                'reply_markup' => $inline_keyboard,
            ]
        );
    }
}
