<?php


namespace App\Exceptions;


use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public $message;
    public $code;
    public $previous;

    public function __construct(int $code, string $message = null, \Throwable $previous = null, array $headers = [])
    {
        $this->code = $code;
        $this->message = $message;
        $this->previous = $previous;
        parent::__construct(200, $message, $previous, $headers, $code);
    }

    /**
     * 转换异常为 HTTP 响应
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'massage' => $this->message,
            'code' => $this->code,
            'status_code' => 200,
            'data' => null,
            'debug' => $this->previous
        ]);
    }

}
