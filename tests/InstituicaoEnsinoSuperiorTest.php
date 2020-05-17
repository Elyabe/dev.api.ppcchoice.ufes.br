<?php

use PHPUnit\Framework\TestCase;

class InstituicaoEnsinoSuperiorTest extends TestCase
{
    // Cliente HTTP para testes de requisição
    private $http;

    // Entity name 
    private $entity;
    
    // Mensagens de sucesso
    const CREATED = 'CREATED';
    
    const UPDATED = 'UPDATED';

    const DELETED = 'DELETED';


    // Error
    const NOT_FOUND = 'NOT_FOUND';

    const EXCEPTION = 'EXCEPTION';

    const CONSTRAINT_NOT_NULL = 'CONSTRAINT_NOT_NULL';
    const CONSTRAINT_NOT_BLANK = 'CONSTRAINT_NOT_BLANK';
    const CONSTRAINT_NOT_NUMERIC = 'CONSTRAINT_NOT_NUMERIC';
    const CONSTRAINT_TYPE_STRING = 'CONSTRAINT_TYPE_INTEGER';
    const CONSTRAINT_NUM_VALID = 'CONSTRAINT_NUM_VALID';
    const CONSTRAINT_TYPE_INTEGER = 'CONSTRAINT_TYPE_INTEGER';
    const CONSTRAINT_MIN_0 = 'CONSTRAINT_MIN_0';

    // Mensagens padrão de retorno
    const STD_MSGS = [
        self::CREATED => 'Instância criada com sucesso.', 
        self::DELETED => 'Instância removida com sucesso.', 
        self::UPDATED => 'Instância atualizada com sucesso.', 
        self::NOT_FOUND => 'Instância não encontrada.', 
        self::EXCEPTION => 'Ocorreu uma exceção ao persistir a instância.',
        self::CONSTRAINT_NOT_NULL => 'Este valor não deve ser nulo.',
        self::CONSTRAINT_NOT_BLANK => 'Este valor não deve ser vazio.',
        self::CONSTRAINT_NOT_NUMERIC => 'Este valor não deve conter caracteres numéricos.',
        self::CONSTRAINT_NUM_VALID => 'O valor deve ser um número válido.',
        self::CONSTRAINT_TYPE_INTEGER => 'Este valor deve ser do tipo integer.',
        self::CONSTRAINT_MIN_0 => 'O código deve ser não negativo e diferente de zero.',
    ];

    public function setUp(){
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://dev.api.ppcchoice.ufes.br/']);
        $this->entity = preg_replace('/Test$/', "", get_class($this));
    }

    public function tearDown() {
        $this->http = null;
        $this->entity = null;
    }

     /** 
    * Gera chave para o array de mensagens padrões de retorno da API.
    * @author Hádamo Egito (http://github.com/hadamo)  
    * @param $category {string} Tipo da mensagem de retorno da API.
    * @return string
    */
    public function generateKey($category)
    {
        switch ($category) {
            case self::CREATED:
            case self::DELETED:
            case self::UPDATED:
                $key = 'message';
                break;
            case self::NOT_FOUND:
            case self::EXCEPTION:
            case self::CONSTRAINT_NOT_NULL:
            case self::CONSTRAINT_NOT_BLANK:
            case self::CONSTRAINT_NOT_NUMERIC:
            case self::CONSTRAINT_NUM_VALID:
            case self::CONSTRAINT_TYPE_INTEGER:
            case self::CONSTRAINT_MIN_0:
                $key = 'error';
                break;
            default:
                $key = 'key';
                break;
        }
        return $key;   
    }

    /** 
    * Gera mensagem para o array de mensagens padrões de retorno da API.
    * @author Hádamo Egito (http://github.com/hadamo)  
    * @param $subpath {string} Identifica o atributo da classe na qual o erro ocorre.
    * @param $category {string} Tipo da mensagem de retorno da API.
    * @return string
    */
    public function generateMessage($category, $subpath = '')
    {
        return 'Entities\\' . $this->entity . ( !empty($subpath) ? '.'  :  ''  ) 
            . $subpath . ':    '  . self::STD_MSGS[$category];
    }

    /** 
    * Gera objeto json com chave da categoria e mensagens padrões de retorno da API.
    * @author Hádamo Egito (http://github.com/hadamo)  
    * @param $subpath {string} Identifica o atributo da classe na qual o erro ocorre.
    * @param $category {string} Tipo da mensagem de retorno da API.
    * @return json
    */
    public function getStdMessage($category = 'NOT_FOUND', $subpath = '')
    {
        $key = $this->generateKey($category);
        return json_encode( [ $key => [$this->generateMessage($category, $subpath)]]);
    }

    /** 
    * Gera objeto json com todas as mensagens de erro.
    * @author Hádamo Egito (http://github.com/hadamo)  
    * @param $violation {Array} Array com categorias como chave e array de strings com todos os subpathes como valor.
    * @return json
    */
    public function getMultipleErrorMessages($violations=[])
    {
        $messages = [];
        //foreach ($violations as $category => $subpathes) {
            foreach ($violations as $content ) {
                $message = $this->generateMessage($content[0],$content[1]);
                array_push($messages,$message);
            }
        //}
        $errorArray = ['error' => $messages];        
        return json_encode($errorArray);
    }

    
    // Testes
    //ROTAS
    //GET
    public function testGetIes()
    {
        $response = $this->http->request('GET', 'instituicoes-ensino-superior', ['http_errors' => FALSE] );

        $this->assertEquals(404, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }*/

    public function testGetIes2()
    {
        $response = $this->http->request('GET', 'instituicoes-ensino-superior', ['http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();
        $message = $this->getStdMessage(self::NOT_FOUND);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertJsonStringEqualsJsonString($contentBody,$message);
    }

    public function testGetIesNaoExistente()
    {
        $response = $this->http->request('GET', 'instituicoes-ensino-superior/100', ['http_errors' => FALSE] );

        $this->assertEquals(404, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testGetIesSucesso()
    {
        $response = $this->http->request('GET', 'instituicoes-ensino-superior/573', ['http_errors' => FALSE] );
        
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testGetIesSucesso2()
    {
        $response = $this->http->request('GET', 'instituicoes-ensino-superior', ['http_errors' => FALSE] );
        
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    //POST
    public function testPostIesSucesso()
    {
        $response = $this->http->request('POST','instituicoes-ensino-superior', 
        [ 'json' => [
        'codIes' => 5963,
        'nome' => 'Instituicao Nova',
        'abreviatura'=> 'IAN'], 
        'http_errors' => FALSE] );
        
        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();
        $message = $this->getStdMessage(self::CREATED);
      
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertJsonStringEqualsJsonString($message,$contentBody);
    }

    //PUT
    public function testPutIesNaoExistente()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/111', ['http_errors' => FALSE] );

        $this->assertEquals(404, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesNaoExistente2()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/2633', ['http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();
        $message = $this->getStdMessage(self::NOT_FOUND);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertJsonStringEqualsJsonString($contentBody,$message);
    }

    public function testPutIesSucesso()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => 'Instituicao Nova Teste',
        'abreviatura'=> 'IAN'], 
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();
        $message = $this->getStdMessage(self::UPDATED);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertJsonStringEqualsJsonString($message,$contentBody);
    }

    //DELETE
    public function testDeleteIesNaoExistente()
    {
        $response = $this->http->request('DELETE', 'instituicoes-ensino-superior/157', ['http_errors' => FALSE] );

        $this->assertEquals(404, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testDeleteIesNaoExistente2()
    {
        $response = $this->http->request('DELETE', 'instituicoes-ensino-superior/2637', ['http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();
        $message = $this->getStdMessage(self::NOT_FOUND);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertJsonStringEqualsJsonString($contentBody,$message);
    }

    public function testDeleteIesSucesso()
    {
        $response = $this->http->request('DELETE', 'instituicoes-ensino-superior/666', ['http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();
        $message = $this->getStdMessage(self::DELETED);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertJsonStringEqualsJsonString($contentBody, $message);
    }

    //CREATE - CÓDIGO ERRADO
    public function testPostIesCodIesVazio()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> '',
        'nome' => 'IES',
        'abreviatura' => 'ABCD'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesCodIesNull()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> null,
        'nome' => 'Instituicao Teste',
        'abreviatura' => 'ABCD'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesCodIesLetra()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> 'fff',
        'nome' => 'Instituição Teste',
        'abreviatura' => 'ABCD'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    //TUDO VAZIO
    public function testPostIesAllVazio()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> '',
        'nome' => '',
        'abreviatura' => ''],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesAllVazioBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> '',
        'nome' => '',
        'abreviatura' => ''],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_BLANK, 'codIes'],
                        [self::CONSTRAINT_TYPE_INTEGER, 'codIes'],
                        [self::CONSTRAINT_NUM_VALID, 'codIes'],
                        [self::CONSTRAINT_NOT_BLANK, 'nome'],
                        [self::CONSTRAINT_NOT_BLANK, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    public function testPutIesAllVazio()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'codIes'=> '',
        'nome' => '',
        'abreviatura' => ''],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesAllVazioBody()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => '',
        'abreviatura' => ''],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_BLANK, 'nome'],
                        [self::CONSTRAINT_NOT_BLANK, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    //TUDO NULL
    public function testPostIesAllNull()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> null,
        'nome' => null,
        'abreviatura' => null],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesAllNullBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> null,
        'nome' => null,
        'abreviatura' => null],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NULL, 'codIes'],
                        [self::CONSTRAINT_NOT_BLANK, 'codIes'],
                        [self::CONSTRAINT_NOT_NULL, 'nome'],
                        [self::CONSTRAINT_NOT_BLANK, 'nome'],
                        [self::CONSTRAINT_NOT_NULL, 'abreviatura'],
                        [self::CONSTRAINT_NOT_BLANK, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    public function testPutIesAllNull()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => null,
        'abreviatura' => null],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesAllNullBody()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => null,
        'abreviatura' => null],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NULL, 'nome'],
                        [self::CONSTRAINT_NOT_BLANK, 'nome'],
                        [self::CONSTRAINT_NOT_NULL, 'abreviatura'],
                        [self::CONSTRAINT_NOT_BLANK, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    // NOME NUMÉRICO
    public function testPostIesNomeNumerico()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> '554',
        'nome' => '111',
        'abreviatura' => 'ABCD'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesNomeNumericoBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes' => 58712,
        'nome' => '21584',
        'abreviatura' => 'GGG'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NUMERIC, 'nome']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    public function testPutIesNomeNumerico()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => '111',
        'abreviatura' => 'ABBB'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesNomeNumericoBody()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => '21584',
        'abreviatura' => 'GUG'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NUMERIC, 'nome']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    //Nome Null
    public function testPostIesNomeNull()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> 777,
        'nome' => null,
        'abreviatura' => 'ABCD'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesNomeNullBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes' => 1255,
        'nome' => null,
        'abreviatura' => 'NULL'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NULL, 'nome'],
                        [self::CONSTRAINT_NOT_BLANK, 'nome']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    public function testPutIesNomeNull()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => null,
        'abreviatura' => 'ABCD'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesNomeNullBody()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => null,
        'abreviatura' => 'NULL'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NULL, 'nome'],
                        [self::CONSTRAINT_NOT_BLANK, 'nome']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    //Nome Vazio
    public function testPostIesNomeVazio()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> 555,
        'nome' => '',
        'abreviatura' => 'ABCD'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesNomeVazioBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes' => 1255,
        'nome' => '',
        'abreviatura' => 'NULL'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_BLANK, 'nome']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    public function testPutIesNomeVazio()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => '',
        'abreviatura' => 'ABCD'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesNomeVazioBody()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => '',
        'abreviatura' => 'NULL'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_BLANK, 'nome']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    //Abreviatura VAZIA
    public function testPostIesAbreviaturaVazio()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> 555,
        'nome' => 'Instituição Teste',
        'abreviatura' => ''],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesAbreviaturaVazioBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes' => 1255,
        'nome' => 'IES',
        'abreviatura' => ''],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_BLANK, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    public function testPutIesAbreviaturaVazio()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => 'Instituição Teste',
        'abreviatura' => ''],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesAbreviaturaVazioBody()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'codIes' => 1255,
        'nome' => 'IES',
        'abreviatura' => ''],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_BLANK, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    //Abreviatura Null
    public function testPostIesAbreviaturaNull()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> 888,
        'nome' => 'Instituicao Teste',
        'abreviatura' => null],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesAbreviaturaNullBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes' => 1255,
        'nome' => 'IES',
        'abreviatura' => null],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NULL, 'abreviatura'],
                        [self::CONSTRAINT_NOT_BLANK, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    public function testPutIesAbreviaturaNull()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => 'Instituicao Teste',
        'abreviatura' => null],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesAbreviaturaNullBody()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'codIes' => 1255,
        'nome' => 'IES',
        'abreviatura' => null],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NULL, 'abreviatura'],
                        [self::CONSTRAINT_NOT_BLANK, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    //Abreviatura Numérica
    public function testPostIesAbreviaturaNumerica()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> 555,
        'nome' => 'Instituicao Teste',
        'abreviatura' => '333'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesAbreviaturaNumericaBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes' => 58712,
        'nome' => 'Instituicao de Teste',
        'abreviatura' => '5287'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NUMERIC, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    public function testPutIesAbreviaturaNumerica()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => 'Teste Instituicao',
        'abreviatura' => '557'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPutIesAbreviaturaNumericaBody()
    {
        $response = $this->http->request('PUT', 'instituicoes-ensino-superior/1500',
        [ 'json' => [
        'nome' => 'Instituicao de Teste',
        'abreviatura' => '5287'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_NOT_NUMERIC, 'abreviatura']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }

    //CodIes deve ser maior que zero
    public function testPostIesCodIesZero()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes'=> 0,
        'nome' => 'Instituicao Teste',
        'abreviatura' => 'GKJF'],
        'http_errors' => FALSE] );

        $this->assertEquals(400, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
    }

    public function testPostIesCodIesZeroBody()
    {
        $response = $this->http->request('POST', 'instituicoes-ensino-superior',
        [ 'json' => [
        'codIes' => -51,
        'nome' => 'Instituicao de Teste',
        'abreviatura' => 'HGFD'],
        'http_errors' => FALSE] );

        $contentType = $response->getHeaders()["Content-Type"][0];
        $contentBody = $response->getBody()->getContents();        
        
        $violations = [[self::CONSTRAINT_MIN_0, 'codIes']];

        $errorArray = $this->getMultipleErrorMessages($violations);       
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("application/json; charset=UTF-8", $contentType);
        $this->assertContains($errorArray,$contentBody);
    }
}