DROP TABLE Cart;
DROP TABLE Shipping_cost;
DROP TABLE Orders;
DROP TABLE Customer;
DROP TABLE Inventory;

CREATE TABLE Customer
(
    customer_id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(50) UNIQUE,
    address VARCHAR(255),

    PRIMARY KEY (customer_id)
);

CREATE TABLE Inventory
(
    item_id INT(11) NOT NULL,
    quantity INT,

    PRIMARY KEY (item_id)
);

CREATE TABLE Orders
(
    order_id INT NOT NULL AUTO_INCREMENT,
    customer_id INT NOT NULL,
    order_status VARCHAR(20) DEFAULT 'UNFULFILLED',
    order_date DATE,
    price FLOAT(8,2),
    order_weight FLOAT(8,2),
    
    PRIMARY KEY (order_id),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
);

CREATE TABLE Cart
(
    customer_id INT NOT NULL,
    item_id INT NOT NULL,
    customerq INT NOT NULL,
    qweight INT NOT NULL,

    PRIMARY KEY (customer_id, item_id),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id),
    FOREIGN KEY (item_id) REFERENCES Inventory(item_id)
);

CREATE TABLE Shipping_cost
(
    shipping_cost_id INT NOT NULL AUTO_INCREMENT,
    weight INT NOT NULL,
    shipping_cost FLOAT NOT NULL,

    PRIMARY KEY (shipping_cost_id)
);
