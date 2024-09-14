<?php

namespace MyCoolPay\Http\Constant;

/**
 * HTTP status codes
 */
class Http
{
    // Methods

    /**
     * The GET method requests a representation of the specified resource.
     * Requests using GET should only retrieve data and should not contain a request content.
     */
    const GET = 'GET';

    /**
     * The HEAD method asks for a response identical to a GET request, but without a response body.
     */
    const HEAD = 'HEAD';

    /**
     * The POST method submits an entity to the specified resource,
     * often causing a change in state or side effects on the server.
     */
    const POST = 'POST';

    /**
     * The PUT method replaces all current representations of the target resource with the request content.
     */
    const PUT = 'PUT';

    /**
     * The DELETE method deletes the specified resource.
     */
    const DELETE = 'DELETE';

    /**
     * The CONNECT method establishes a tunnel to the server identified by the target resource.
     */
    const CONNECT = 'CONNECT';

    /**
     * The OPTIONS method describes the communication options for the target resource.
     */
    const OPTIONS = 'OPTIONS';

    /**
     * The TRACE method performs a message loop-back test along the path to the target resource.
     */
    const TRACE = 'TRACE';

    /**
     * The PATCH method applies partial modifications to a resource.
     */
    const PATCH = 'PATCH';


    // Information responses

    /**
     * This interim response indicates that the client should continue the request
     * or ignore the response if the request is already finished.
     */
    const CONTINUE_ = 100;

    /**
     * This code is sent in response to an Upgrade request header from the client
     * and indicates the protocol the server is switching to.
     */
    const SWITCHING_PROTOCOLS = 101;

    /**
     * This code indicates that the server has received and is processing the request,
     * but no response is available yet.
     */
    const PROCESSING = 102;

    /**
     * This status code is primarily intended to be used with the Link header,
     * letting the user agent start preloading resources while the server prepares a response.
     */
    const EARLY_HINTS = 103;


    // Successful responses

    /**
     * The request succeeded. The result meaning of "success" depends on the HTTP method:
     * <ul><li><b>GET</b>: The resource has been fetched and transmitted in the message body.</li>
     * <li><b>HEAD</b>: The representation headers are included in the response without any message body.</li>
     * <li><b>PUT</b> or <b>POST</b>: The resource describing the result of the action is transmitted in the message body.</li>
     * <li><b>TRACE</b>: The message body contains the request message as received by the server.</li></ul>
     */
    const OK = 200;

    /**
     * The request succeeded, and a new resource was created as a result.
     * This is typically the response sent after POST requests, or some PUT requests.
     */
    const CREATED = 201;

    /**
     * The request has been received but not yet acted upon.
     * It is noncommittal, since there is no way in HTTP to later send an asynchronous response
     * indicating the outcome of the request.
     * It is intended for cases where another process or server handles the request, or for batch processing.
     */
    const ACCEPTED = 202;

    /**
     * This response code means the returned metadata is not exactly the same as is available from the origin server,
     * but is collected from a local or a third-party copy.
     * This is mostly used for mirrors or backups of another resource.
     * Except for that specific case, the 200 OK response is preferred to this status.
     */
    const NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * There is no content to send for this request, but the headers may be useful.
     * The user agent may update its cached headers for this resource with the new ones.
     */
    const NO_CONTENT = 204;

    /**
     * Tells the user agent to reset the document which sent this request.
     */
    const RESET_CONTENT = 205;

    /**
     * This response code is used when the Range header is sent from the client to request only part of a resource.
     */
    const PARTIAL_CONTENT = 206;

    /**
     * Conveys information about multiple resources, for situations where multiple status codes might be appropriate.
     */
    const MULTI_STATUS = 207;

    /**
     * Used inside a <dav:propstat> response element to avoid repeatedly enumerating the internal members
     * of multiple bindings to the same collection.
     */
    const ALREADY_REPORTED = 208;

    /**
     * The server has fulfilled a GET request for the resource, and the response is a representation of the result
     * of one or more instance-manipulations applied to the current instance.
     */
    const IM_USED = 226;

    // Redirection responses

    /**
     * The request has more than one possible response. The user agent or user should choose one of them.
     * (There is no standardized way of choosing one of the responses,
     * but HTML links to the possibilities are recommended so the user can pick.)
     */
    const MULTIPLE_CHOICES = 300;

    /**
     * The URL of the requested resource has been changed permanently. The new URL is given in the response.
     */
    const MOVED_PERMANENTLY = 301;

    /**
     * This response code means that the URI of requested resource has been changed temporarily.
     * Further changes in the URI might be made in the future.
     * Therefore, this same URI should be used by the client in future requests.
     */
    const FOUND = 302;

    /**
     * The server sent this response to direct the client to get the requested resource at another URI
     * with a GET request.
     */
    const SEE_OTHER = 303;

    /**
     * This is used for caching purposes. It tells the client that the response has not been modified,
     * so the client can continue to use the same cached version of the response.
     */
    const NOT_MODIFIED = 304;

    /**
     * @deprecated USE_PROXY
     * Defined in a previous version of the HTTP specification to indicate that a requested response must be accessed
     * by a proxy. It has been deprecated due to security concerns regarding in-band configuration of a proxy.
     */
    const USE_PROXY = 305;

    /**
     * The server sends this response to direct the client to get the requested resource at another URI
     * with same method that was used in the prior request.
     * This has the same semantics as the 302 Found HTTP response code, with the exception that
     * the user agent must not change the HTTP method used: if a POST was used in the first request,
     * a POST must be used in the second request.
     */
    const TEMPORARY_REDIRECT = 307;

    /**
     * This means that the resource is now permanently located at another URI,
     * specified by the Location: HTTP Response header.
     * This has the same semantics as the 301 Moved Permanently HTTP response code, with the exception that
     * the user agent must not change the HTTP method used: if a POST was used in the first request,
     * a POST must be used in the second request.
     */
    const PERMANENT_REDIRECT = 308;


    // Client error responses

    /**
     * The server cannot or will not process the request due to something that is perceived to be a client error
     * (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
     */
    const BAD_REQUEST = 400;

    /**
     * Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated".
     * That is, the client must authenticate itself to get the requested response.
     */
    const UNAUTHORIZED = 401;

    /**
     * This response code is reserved for future use.
     * The initial aim for creating this code was using it for digital payment systems,
     * however this status code is used very rarely and no standard convention exists.
     */
    const PAYMENT_REQUIRED = 402;

    /**
     * The client does not have access rights to the content; that is, it is unauthorized,
     * so the server is refusing to give the requested resource. Unlike 401 Unauthorized,
     * the client's identity is known to the server.
     */
    const FORBIDDEN = 403;

    /**
     * The server cannot find the requested resource. In the browser, this means the URL is not recognized.
     * In an API, this can also mean that the endpoint is valid but the resource itself does not exist.
     * Servers may also send this response instead of 403 Forbidden to hide the existence of a resource
     * from an unauthorized client.
     * This response code is probably the most well known due to its frequent occurrence on the web.
     */
    const NOT_FOUND = 404;

    /**
     * The request method is known by the server but is not supported by the target resource.
     * For example, an API may not allow calling DELETE to remove a resource.
     */
    const METHOD_NOT_ALLOWED = 405;

    /**
     * This response is sent when the web server, after performing server-driven content negotiation,
     * doesn't find any content that conforms to the criteria given by the user agent.
     */
    const NOT_ACCEPTABLE = 406;

    /**
     * This is similar to 401 Unauthorized but authentication is needed to be done by a proxy.
     */
    const PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * This response is sent on an idle connection by some servers, even without any previous request by the client.
     * It means that the server would like to shut down this unused connection. This response is used much more
     * since some browsers, like Chrome, Firefox 27+, or IE9, use HTTP pre-connection mechanisms to speed up surfing.
     * Also note that some servers merely shut down the connection without sending this message.
     */
    const REQUEST_TIMEOUT = 408;

    /**
     * This response is sent when a request conflicts with the current state of the server.
     */
    const CONFLICT = 409;

    /**
     * This response is sent when the requested content has been permanently deleted from server,
     * with no forwarding address. Clients are expected to remove their caches and links to the resource.
     * The HTTP specification intends this status code to be used for "limited-time, promotional services".
     * APIs should not feel compelled to indicate resources that have been deleted with this status code.
     */
    const GONE = 410;

    /**
     * Server rejected the request because the Content-Length header field is not defined and the server requires it.
     */
    const LENGTH_REQUIRED = 411;

    /**
     * The client has indicated preconditions in its headers which the server does not meet.
     */
    const PRECONDITION_FAILED = 412;

    /**
     * Request entity is larger than limits defined by server.
     * The server might close the connection or return an Retry-After header field.
     */
    const PAYLOAD_TOO_LARGE = 413;

    /**
     * The URI requested by the client is longer than the server is willing to interpret.
     */
    const URI_TOO_LONG = 414;

    /**
     * The media format of the requested data is not supported by the server, so the server is rejecting the request.
     */
    const UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * The range specified by the Range header field in the request cannot be fulfilled.
     * It's possible that the range is outside the size of the target URI's data.
     */
    const RANGE_NOT_SATISFIABLE = 416;

    /**
     * This response code means the expectation indicated by the Expect request header field cannot be met by the server.
     */
    const EXPECTATION_FAILED = 417;

    /**
     * The server refuses the attempt to brew coffee with a teapot.
     */
    const TEAPOT = 418;

    /**
     * The request was directed at a server that is not able to produce a response.
     * This can be sent by a server that is not configured to produce responses for the combination of scheme
     * and authority that are included in the request URI.
     */
    const MISDIRECTED_REQUEST = 421;

    /**
     * The request was well-formed but was unable to be followed due to semantic errors.
     */
    const UNPROCESSABLE_ENTITY = 422;

    /**
     * The resource that is being accessed is locked.
     */
    const LOCKED = 423;

    /**
     * The request failed due to failure of a previous request.
     */
    const FAILED_DEPENDENCY = 424;

    /**
     * Indicates that the server is unwilling to risk processing a request that might be replayed.
     */
    const TOO_EARLY = 425;

    /**
     * The server refuses to perform the request using the current protocol
     * but might be willing to do so after the client upgrades to a different protocol.
     * The server sends an Upgrade header in a 426 response to indicate the required protocol(s).
     */
    const UPGRADE_REQUIRED = 426;

    /**
     * The origin server requires the request to be conditional. This response is intended to prevent
     * the 'lost update' problem, where a client GETs a resource's state, modifies it and PUTs it back to the server,
     * when meanwhile a third party has modified the state on the server, leading to a conflict.
     */
    const PRECONDITION_REQUIRED = 428;

    /**
     * The user has sent too many requests in a given amount of time ("rate limiting").
     */
    const TOO_MANY_REQUESTS = 429;

    /**
     * The server is unwilling to process the request because its header fields are too large.
     * The request may be resubmitted after reducing the size of the request header fields.
     */
    const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    /**
     * The user agent requested a resource that cannot legally be provided, such as a web page censored by a government.
     */
    const UNAVAILABLE_FOR_LEGAL_REASON = 451;


    // Server error responses

    /**
     * The server has encountered a situation it does not know how to handle.
     */
    const INTERNAL_SERVER_ERROR = 500;

    /**
     * The request method is not supported by the server and cannot be handled. The only methods that servers
     * are required to support (and therefore that must not return this code) are GET and HEAD.
     */
    const NOT_IMPLEMENTED = 501;

    /**
     * This error response means that the server,
     * while working as a gateway to get a response needed to handle the request, got an invalid response.
     */
    const BAD_GATEWAY = 502;

    /**
     * The server is not ready to handle the request. Common causes are a server that is down for maintenance
     * or that is overloaded. Note that together with this response, a user-friendly page explaining the problem
     * should be sent. This response should be used for temporary conditions and the Retry-After HTTP header should,
     * if possible, contain the estimated time before the recovery of the service.
     * The webmaster must also take care about the caching-related headers that are sent along with this response,
     * as these temporary condition responses should usually not be cached.
     */
    const SERVICE_UNAVAILABLE = 503;

    /**
     * This error response is given when the server is acting as a gateway and cannot get a response in time.
     */
    const GATEWAY_TIMEOUT = 504;

    /**
     * The HTTP version used in the request is not supported by the server.
     */
    const HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * The server has an internal configuration error: the chosen variant resource is configured to engage
     * in transparent content negotiation itself, and is therefore not a proper end point in the negotiation process.
     */
    const VARIANT_ALSO_NEGOTIATES = 506;

    /**
     * The method could not be performed on the resource because
     * the server is unable to store the representation needed to successfully complete the request.
     */
    const INSUFFICIENT_STORAGE = 507;

    /**
     * The server detected an infinite loop while processing the request.
     */
    const LOOP_DETECTED = 508;

    /**
     * Further extensions to the request are required for the server to fulfill it.
     */
    const NOT_EXTENDED = 510;

    /**
     * Indicates that the client needs to authenticate to gain network access.
     */
    const NETWORK_AUTHENTICATION_REQUIRED = 511;
}
