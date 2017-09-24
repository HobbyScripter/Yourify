<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 15.07.2017
 * Time: 13:00
 */

namespace Yourify\Exceptions;


use Symfony\Component\HttpKernel\Exception\HttpException;

class RepositoryException extends HttpException
{
    public function __construct($statusCode, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        parent::__construct(409, $message, $previous, array(), $code);
    }
}