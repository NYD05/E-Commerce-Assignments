<?php

require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController
{
    private $customerModel;


    public function __construct()
    {
        $this->customerModel = new CustomerClass();
    }


    public function register($data)
    {
        $name = $data['name'];
        $email = $data['email'];
        $pass = $data['pass'];
        $country = $data['country'];
        $city = $data['city'];
        $contact = $data['contact'];

        // Check if email already exists
        if ($this->customerModel->emailExists($email)) {

            return [
                'success' => false,
                'message' => 'An account with this email already exists.'
            ];
        }

        // Create customer
        $created = $this->customerModel->addCustomer(
            $name,
            $email,
            $pass,
            $country,
            $city,
            $contact
        );

        if ($created) {

            return [
                'success' => true,
                'message' => 'Registration successful.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Registration failed. Please try again.'
        ];
    }


    public function getCustomerIdByEmail($email)
    {
        $customer = $this->customerModel->getCustomerByEmail($email);

        if ($customer) {
            return $customer['customer_id'];
        }

        return null;
    }


    public function login($email, $pass)
    {
        $customer = $this->customerModel->getCustomerByEmail($email);

        if (!$customer) {

            return [
                'success' => false,
                'message' => 'Invalid email or password.'
            ];
        }

        if (!password_verify($pass, $customer['customer_pass'])) {

            return [
                'success' => false,
                'message' => 'Invalid email or password.'
            ];
        }

        return [
            'success' => true,
            'customer' => $customer
        ];
    }
}

?>