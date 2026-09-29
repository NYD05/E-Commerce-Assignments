<?php

require_once __DIR__ . '/../core/db_class.php';

class CustomerClass extends Database
{
    public function emailExists($email)
    {
        $sql = "SELECT customer_email FROM customer WHERE customer_email = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;

        $stmt->close();

        return $exists;
    }


    public function addCustomer(
        $name,
        $email,
        $pass,
        $country,
        $city,
        $contact
    ) {
        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

        $sql = "INSERT INTO customer
                (
                    customer_name,
                    customer_email,
                    customer_pass,
                    customer_country,
                    customer_city,
                    customer_contact
                )
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssssss",
            $name,
            $email,
            $hashedPassword,
            $country,
            $city,
            $contact
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }


    public function getCustomerByEmail($email)
    {
        $sql = "SELECT * FROM customer WHERE customer_email = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        $stmt->close();

        return $customer;
    }


    public function getCustomerById($id)
    {
        $sql = "SELECT * FROM customer WHERE customer_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        $stmt->close();

        return $customer;
    }
}

?>