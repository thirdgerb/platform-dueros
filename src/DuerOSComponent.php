<?php

namespace Commune\Platform\DuerOS;

use Commune\Chatbot\Framework\Component\ComponentOption;
use Commune\Platform\DuerOS\Constants\CommonIntents as DuerIntents;
use Commune\Components\Predefined\Intents\Attitudes;
use Commune\Components\Predefined\Intents\Loop;
use Commune\Platform\DuerOS\Constants\Dictionary;
use Commune\Platform\DuerOS\Providers\RenderServiceProvider;

/**
 * @property-read string $privateKey 私钥的文件路径.
 * @property-read array $intentMapping duerOS 的intent 变成本地的Intent
 * @property-read array[] $entityMapping duerOs 的entity 变成本地的entity
 * @property-read string $rePrompt 用户没有及时响应多轮对话时的回复.
 * @property-read string $renderServiceProvider 默认的rendering服务注册
 * @property-read string[] $sceneNames 场景映射的技能名称
 */
class DuerOSComponent extends ComponentOption
{

    public static function stub(): array
    {
        return [

            // 回复渲染所用服务.
            'renderServiceProvider' => RenderServiceProvider::class,

            'privateKey' => '',

            // 系统重试语句
            'rePrompt' => '没听清, 请再说一次?',

            /**
             * DuerOS intent name 和本地 intent name 的映射关系.
             */
            'intentMapping' => [
                // 默认
                DuerIntents::COMMON_DEFAULT => null,
                // cancel
                DuerIntents::COMMON_CANCEL => Attitudes\DontInt::class,
                // affirm
                DuerIntents::COMMON_CONFIRM => Attitudes\AffirmInt::getContextName(),

                // next
                DuerIntents::COMMON_NEXT => Loop\NextInt::getContextName(),
                // previous
                DuerIntents::COMMON_PREVIOUS => Loop\PreviousInt::getContextName(),

            ],

            // 实体映射的名称, 可用于 duer实体词典和本地的转义
            'entityMapping' => [
                'dialogue.ordinal' => [
                    Dictionary::SYSTEM_NUMBER => 'ordinal'
                ]

            ],

            // 场景名称, 可以通过 scene 获取技能的名称.
            'sceneNames' => [
            ],
        ];
    }


    protected function doBootstrap(): void
    {
        // 注册 render
        $this->app->registerProcessService($this->renderServiceProvider);
    }

}