<?php

namespace Botble\Base\Events;

use Eloquent;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use stdClass;

class CreatedContentEvent extends Event
{
    use SerializesModels;

    /**
     * @var string
     */
    public $screen;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Eloquent|false
     */
    public $data;

    /**
     * CreatedContentEvent constructor.
     * @param string $screen
     * @param Request $request
     * @param Eloquent|false|stdClass $data
     */
    public function __construct($screen, $request, $data)
    {
        echo $this->screen = $screen;
        echo $this->request = $request;
        echo $this->data = $data;
    }
}
