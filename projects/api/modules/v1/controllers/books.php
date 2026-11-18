<?php
/**
 * @filesource api/modules/v1/controllers/books.php
 */

namespace V1\Books;

use Kotchasan\ApiController;
use Kotchasan\Http\Request;
use Kotchasan\Http\Response;

/**
 * REST API of the books.
 *
 * Kotchasan\Router sends api.php/{module}/{controller}/{action} to
 * Kotchasan\ApiController, which calls {Module}\{Controller}\Controller::{action}().
 * A number in place of the action is the id, and the action is then index:
 *
 *   GET    api.php/v1/books       list    ?q=word&page=2
 *   GET    api.php/v1/books/3     one book
 *   POST   api.php/v1/books       add      {title, author, price}   needs the token
 *   PUT    api.php/v1/books/3     change   {title, author, price}   needs the token
 *   DELETE api.php/v1/books/3     delete                            needs the token
 *
 * The token goes in the header "Authorization: Bearer <token>" (api_tokens in
 * settings/config.php). Every answer has the same keys:
 * success, message, code, data, errors, request_id.
 */
class Controller extends ApiController
{
    /**
     * Every method of HTTP comes here: the URL names the book, the method says what to do.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // The first call creates datas/api.sqlite and the table
        Model::install();

        $id = $request->get('id')->toInt();
        $method = $request->getMethod();

        if ($method === 'GET') {
            return $id > 0 ? $this->show($id) : $this->search($request);
        }

        // Writing needs a token. validateTokenBearer() throws ApiException (401),
        // which ApiController turns into a JSON answer.
        self::validateTokenBearer($request);

        if ($method === 'POST' && $id === 0) {
            return $this->store($request);
        }
        if ($method === 'PUT' && $id > 0) {
            return $this->update($request, $id);
        }
        if ($method === 'DELETE' && $id > 0) {
            return $this->destroy($id);
        }

        return $this->errorResponse('Method not allowed', 405);
    }

    /**
     * GET api.php/v1/books?q=&page=
     *
     * @param Request $request
     *
     * @return Response
     */
    private function search(Request $request)
    {
        return $this->successResponse(Model::search(
            $request->get('q')->topic(100),
            $request->get('page', 1)->toInt()
        ));
    }

    /**
     * GET api.php/v1/books/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    private function show($id)
    {
        $book = Model::find($id);

        return $book === null ? $this->errorResponse('Book not found', 404) : $this->successResponse($book);
    }

    /**
     * POST api.php/v1/books
     *
     * @param Request $request
     *
     * @return Response
     */
    private function store(Request $request)
    {
        $data = $this->read($request, $errors);
        if (!empty($errors)) {
            return $this->validationErrorResponse($errors);
        }
        $id = Model::add($data);

        // 201 Created, with the new book
        return $this->successResponse(Model::find($id), 'Book created', 201);
    }

    /**
     * PUT api.php/v1/books/{id}
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    private function update(Request $request, $id)
    {
        if (Model::find($id) === null) {
            return $this->errorResponse('Book not found', 404);
        }
        $data = $this->read($request, $errors);
        if (!empty($errors)) {
            return $this->validationErrorResponse($errors);
        }
        Model::change($id, $data);

        return $this->successResponse(Model::find($id), 'Book updated');
    }

    /**
     * DELETE api.php/v1/books/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    private function destroy($id)
    {
        if (!Model::remove($id)) {
            return $this->errorResponse('Book not found', 404);
        }

        return $this->successResponse(['id' => $id], 'Book deleted');
    }

    /**
     * Checks the body of POST and PUT (JSON or a form) and returns the clean values.
     *
     * @param Request $request
     * @param array   $errors  field => [messages], empty when the data is good
     *
     * @return array title, author, price
     */
    private function read(Request $request, &$errors)
    {
        // validate() of ApiController reads the body and checks the rules
        list($valid, $errors) = $this->validate($request, [
            'title' => 'required|max:150',
            'author' => 'required|max:100',
            'price' => 'required|numeric'
        ]);
        if ($valid && $request->post('price')->toFloat() < 0) {
            $errors['price'] = ['The price must not be negative.'];
        }

        return [
            'title' => $request->post('title')->topic(150),
            'author' => $request->post('author')->topic(100),
            'price' => round($request->post('price')->toFloat(), 2)
        ];
    }
}
