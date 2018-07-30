<?php
/**
 * Copyright (c) 2016 MZ Desenvolvimento de Sistemas LTDA - All Rights Reserved
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Core;

/**
 * Allow application to serve system resources
 */
abstract class Service
{
    private $application;

    /**
     * Constructor for a new instance of Service
     * @param Application $application Main application
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Get the application object
     * @return Application application object
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Get response object from context, JsonResponse or HtmlResponse
     * @param  string $format default format, json or html
     * @return Response contextual response object
     */
    public function getResponse($format = null)
    {
        return $this->getApplication()->getResponse($format);
    }
}
