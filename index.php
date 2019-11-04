<?php

use router\Router;
use http\HTTPException;
use http\HTTPHeader;
use http\HTTPStatusCode;

use controllers\AjaxController;
use controllers\AuthController;
use controllers\BusController;
use controllers\HotelController;
use controllers\InsuranceController;
use controllers\InvoiceController;
use controllers\TripController;
use controllers\UserController;
use controllers\ErrorController;

/**
 * Access point of all requests<br>
 * Routes the given path with the given method to the correct Controller
 * @author Lukas
 */

session_start();
require_once 'helpers/Autoloader.php';



$authFunction = function () {
    if (AuthController::authenticate()){
        return true;
    }
    Router::redirect("/login");
    return false;
};

Router::route("GET", "/login", function () {
    AuthController::loginView();
});

Router::route("GET", "/registration", function () {
    AuthController::registerView();
});

Router::route("POST", "/registration", function () {
    if(UserController::register()){
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            Router::redirect("/admin/users");
        }else{
            Router::redirect("/");
        }
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route("POST", "/login", function () {
    if(UserController::login()){
        Router::redirect("/");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route("GET", "/logout", function () {
    UserController::logout();
    Router::redirect("/");
});

Router::route_auth("GET", "/", $authFunction, function () {
    if(!UserController::getHomepage()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/admin", $authFunction, function () {
    if(!UserController::getHomepage()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/admin/users", $authFunction, function () {
    if(!UserController::getAllUsers()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/admin/users/{id}", $authFunction, function ($id) {
    if(UserController::deleteUser($id)){
        Router::redirect("/admin/users");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

//Not in use
Router::route_auth("DELETE", "/profile", $authFunction, function () {
    if(UserController::deleteSelf()){
        Router::redirect("/logout");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("PUT", "/admin/users/{id}", $authFunction, function ($id) {
    UserController::changeRole($id);
    HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
});

Router::route_auth("GET", "/travelers", $authFunction, function () {
    if(!UserController::getParticipants()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("POST", "/travelers", $authFunction, function () {
    if(UserController::createParticipant()){
        Router::redirect("/travelers");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/travelers/{id}", $authFunction, function ($id) {
    if(UserController::deleteParticipant($id)){
        Router::redirect("/travelers");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/admin/buses", $authFunction, function () {
    if(!BusController::getAllBuses()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("POST", "/admin/buses", $authFunction, function () {
    if(BusController::createBus()){
        Router::redirect("/admin/buses");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/admin/buses/{id}", $authFunction, function ($id) {
    if(BusController::deleteBus($id)){
        Router::redirect("/admin/buses");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/admin/hotels", $authFunction, function () {
    if(!HotelController::getAllHotels()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("POST", "/admin/hotels", $authFunction, function () {
    if(HotelController::createHotel()){
        Router::redirect("/admin/hotels");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/admin/hotels/{id}", $authFunction, function ($id) {
    if(HotelController::deleteHotel($id)){
        Router::redirect("/admin/hotels");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/admin/insurances", $authFunction, function () {
    if(!InsuranceController::getAllInsurances()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("POST", "/admin/insurances", $authFunction, function () {
    if(InsuranceController::createInsurance()){
        Router::redirect("/admin/insurances");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/admin/insurances/{id}", $authFunction, function ($id) {
    if(InsuranceController::deleteInsurance($id)){
        Router::redirect("/admin/insurances");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

//no use of $authFunctin necessary to allow users without a loggin to see the packageOverview
Router::route("GET", "/packageOverview", function () {
    if(!TripController::getAllTrips()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "admin/packageOverview", $authFunction, function () {
        if(!TripController::getAllTrips()){
            HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
        }
});

Router::route_auth("GET", "admin/tripTemplates", $authFunction, function () {
        if(!TripController::getAllTripTemplates()){
            HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
        }
});

Router::route_auth("POST", "/admin/tripTemplates", $authFunction, function () {
    if(TripController::createTripTemplate()){
        Router::redirect("/admin/tripTemplates");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/admin/tripTemplates/{id}", $authFunction, function ($id) {
    if(TripController::deleteTripTemplate($id)){
        Router::redirect("/admin/tripTemplates");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route("GET", "/packageOverview/package/{id}", function ($id) {
    if(!TripController::getTripTemplate($id)){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("POST", "/packageOverview/package", $authFunction, function () {
    if(TripController::bookTrip()){
        Router::redirect("/packageOverview");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/admin/tripTemplates/package/{id}", $authFunction, function ($id) {
    if(!TripController::getTripTemplate($id)){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("PUT", "/admin/tripTemplates/package/{id}", $authFunction, function ($id) {
    if(TripController::changeBookableOfTripTemplate($id)){
        Router::redirect("/admin/packageOverview");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("POST", "/admin/tripTemplates/package", $authFunction, function () {
    $id = TripController::createDayprogram();
    if($id){
        Router::redirect("/admin/tripTemplates/package/".$id);
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/admin/tripTemplates/package/{id}/{id}", $authFunction, function ($dayprogramId, $tripTemplateId) {
    if(TripController::deleteDayprogram($dayprogramId)){
        Router::redirect("/admin/tripTemplates/package/".$tripTemplateId);
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/admin/bookedTrips/detail/{id}", $authFunction, function ($id) {
    if(TripController::cancelTrip($id)){
        Router::redirect("/admin/packageOverview");
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/bookedTrips/detail/{id}", $authFunction, function ($id) {
    if(!TripController::getBookedTrip($id)){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/admin/bookedTrips/detail/{id}", $authFunction, function ($id) {
    if(!TripController::getBookedTrip($id)){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
    
});

Router::route_auth("POST", "/admin/bookedTrips/detail", $authFunction, function () {
    $tripId = InvoiceController::createInvoice();
    if($tripId){
        Router::redirect("/admin/bookedTrips/detail/".$tripId);
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("DELETE", "/admin/bookedTrips/detail/{id}/{id}", $authFunction, function ($invoiceId, $tripId) {
    if(InvoiceController::deleteInvoice($invoiceId)){
        Router::redirect("/admin/bookedTrips/detail/".$tripId);
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("PUT", "/admin/bookedTrips/detail/{id}", $authFunction, function ($id) {
    if(TripController::lockInvoicesRegistered($id)){
        Router::redirect("/admin/bookedTrips/detail/finalSettlement/".$id);
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("PUT", "/admin/bookedTrips/detail/{id}/{id}", $authFunction, function ($id, $routeInfo) {
    if(TripController::unlockInvoicesRegistered($id)){
        Router::redirect("/admin/bookedTrips/detail/".$id);
    }else{
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/admin/bookedTrips/detail/invoices/{id}", $authFunction, function ($id) {
    if(!InvoiceController::getCustomersInvoice($id)){
       HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "/bookedTrips/detail/invoices/{id}", $authFunction, function ($id) {
    if(!InvoiceController::getCustomersInvoice($id)){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route_auth("GET", "admin/bookedTrips/detail/finalSettlement/{id}", $authFunction, function ($id) {
    if(!InvoiceController::getFinalSettlement($id)){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route("POST", "ajaxEmail", function () {
    if(!AjaxController::checkEmail()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});

Router::route("POST", "ajaxLogin", function () {
    if(!AjaxController::checkLogin()){
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    }
});



try {
    HTTPHeader::setHeader("Access-Control-Allow-Origin: *");
    HTTPHeader::setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");
    HTTPHeader::setHeader("Access-Control-Allow-Headers: Authorization, Location, Origin, Content-Type, X-Requested-With");
    if($_SERVER['REQUEST_METHOD']=="OPTIONS") {
        HTTPHeader::setStatusHeader(HTTPStatusCode::HTTP_204_NO_CONTENT);
    } else {
        Router::call_route($_SERVER['REQUEST_METHOD'], $_SERVER['PATH_INFO']);
    }
} catch (HTTPException $exception) {
    $exception->getHeader();
    ErrorController::show404();
}





