<?php

namespace Hansanghyeon;

use Alfred\Workflows\Workflow;

class Search
{

    /**
     * @var Workflow
     */
    private $workflow;

    public function __construct()
    {
        $this->workflow = new Workflow();
    }

    public function search($query, $result)
    {
        $this->workflow->result()
            ->uid('papago search')
            ->title($result)
            // ->subtitle('subtitle')
            ->arg($result)
            ->valid(true);

        return $this->workflow->output();
    }
}
