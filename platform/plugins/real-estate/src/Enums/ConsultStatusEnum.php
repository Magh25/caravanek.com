<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static ConsultStatusEnum UNREAD()
 * @method static ConsultStatusEnum READ()
 */
class ConsultStatusEnum extends Enum
{
    public const READ = 'approved';
    public const UNREAD = 'pending';
    // public const PENDING = '';
    // public const APPROVED = '';
    public const CANCELED = 'canceled';
    public const COMPLETED = 'completed';

    /**
     * @var string
     */
    public static $langPath = 'plugins/real-estate::consult.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::UNREAD:
                return Html::tag('span', self::UNREAD()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::READ:
                return Html::tag('span', self::READ()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::CANCELED:
                return Html::tag('span', self::CANCELED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            case self::COMPLETED:
                return Html::tag('span', self::COMPLETED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            default:
                return null;
        }
    }
}
