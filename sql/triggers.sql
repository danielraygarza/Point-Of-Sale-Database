CREATE DEFINER=`danielgarza`@`%` TRIGGER `customers_BEFORE_UPDATE` BEFORE UPDATE ON `customers` FOR EACH ROW BEGIN
  -- event: customers places order
  IF OLD.total_spent_toDate < NEW.total_spent_toDate THEN
	-- checking if total spent value passes a multiple of 100
    SET @hundreds = FLOOR(NEW.total_spent_toDate / 100) - FLOOR(OLD.total_spent_toDate / 100);
     -- condition
    IF @hundreds > 0 THEN
		-- action: add 10 dollars to store credit
      SET NEW.store_credit = NEW.store_credit + (@hundreds * 10);
    END IF;
  END IF;
END

CREATE DEFINER=`danielgarza`@`%` TRIGGER `order_items_AFTER_INSERT` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
	-- action: order placed
    DECLARE reorderThreshold INT;
    DECLARE currentInventory INT;

	-- gets threshold for item
	SELECT Reorder_Threshold INTO reorderThreshold
	FROM items
	WHERE Item_ID = NEW.Item_ID;

	-- gets current inventory for item
	SELECT Inventory_Amount INTO currentInventory
	FROM inventory
	WHERE Item_ID = NEW.Item_ID AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);

	-- condition: checks if current inventory is below the threshold
	IF currentInventory < reorderThreshold THEN
        UPDATE inventory
        -- action: add inventory
		SET Inventory_Amount = Inventory_Amount + 100,
			Last_Stock_Shipment_date = NOW()
		WHERE Item_ID = NEW.Item_ID AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);
	END IF;
    
    -- conidtion: if item is a pizza, check dough, sauce, and cheese
    IF (SELECT Item_Type FROM items WHERE Item_ID = NEW.Item_ID) = 'Pizza' THEN
        -- action: check/update dough, sauce, cheese
        
		-- Check Dough inventory
		UPDATE inventory
		SET Inventory_Amount = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Dough') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Dough')
				THEN Inventory_Amount + 50
				ELSE Inventory_Amount
			END,
			Last_Stock_Shipment_date = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Dough') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Dough')
				THEN NOW()
				ELSE Last_Stock_Shipment_date
			END
		WHERE Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Dough') AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);

		-- Check Sauce inventory
		UPDATE inventory
		SET Inventory_Amount = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Sauce') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Sauce')
				THEN Inventory_Amount + 50
				ELSE Inventory_Amount
			END,
			Last_Stock_Shipment_date = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Sauce') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Sauce')
				THEN NOW()
				ELSE Last_Stock_Shipment_date
			END
		WHERE Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Sauce') AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);

		-- Check Cheese inventory
		UPDATE inventory
		SET Inventory_Amount = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Cheese') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Cheese')
				THEN Inventory_Amount + 50
				ELSE Inventory_Amount
			END,
			Last_Stock_Shipment_date = CASE
				WHEN Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Cheese') AND Inventory_Amount < (SELECT Reorder_Threshold FROM items WHERE Item_Name = 'Cheese')
				THEN NOW()
				ELSE Last_Stock_Shipment_date
			END
		WHERE Item_ID = (SELECT Item_ID FROM items WHERE Item_Name = 'Cheese') AND Store_ID = (SELECT Store_ID FROM orders WHERE Order_ID = NEW.Order_ID);

    END IF;
END

CREATE DEFINER=`danielgarza`@`%` TRIGGER `employee_BEFORE_UPDATE` BEFORE UPDATE ON `employee` FOR EACH ROW BEGIN
    IF NEW.active_employee = 0 THEN
        SET NEW.clocked_in = 0;
    END IF;
END