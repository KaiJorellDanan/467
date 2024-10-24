CREATE TABLE Customer
(
    customer_id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50)
    last_name VARCHAR(50)
    email VARCHAR(50) UNIQUE,
    city VARCHAR(50)
    user_state VARCHAR(2)
    zip VARCHAR(50)
    phone VARCHAR(50);

    PRIMARY KEY (customer_id)
);

CREATE TABLE Inventory
(
    item_id INT
    item_desc VARCHAR(50)
    quantity INT

    PRIMARY KEY (item_id)
);

CREATE TABLE Order
(
    order_id INT NOT NULL AUTO_INCREMENT,
    customer_id INT
    order_status VARCHAR(20)
    price FLOAT(8,2)
    order_weight FLOAT(8,2)
    rate FLOAT(8,2)

    PRIMARY KEY (order_id)
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
);

CREATE TABLE Shipping
(
    shipping_id INT NOT NULL AUTO_INCREMENT
    order_id INT
    item_id INT
    item_name VARCHAR(50)
    quantity INT

    PRIMARY KEY (shipping_id)
    FOREIGN KEY (order_id) REFERENCES Order(order_id)
    FOREIGN KEY (item_id) REFERENCES Inventory(item_id)
);
