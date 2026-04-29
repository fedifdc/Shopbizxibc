Abandoned Cart Webhook Plugin
Overview
The WooCommerce Abandoned Cart Webhook Plugin enables store owners to capture and send abandoned cart data, including customer information, to a specified webhook URL when customers leave their cart without completing a purchase. This data helps in recovering lost sales by following up with potential customers via email, SMS, or other notification methods.
Key Features
•	Capture Customer and Cart Data: Collect customer details like email, phone number, billing and shipping addresses, along with the abandoned cart items.
•	Webhook Integration: Automatically sends the cart data to a specified webhook URL, enabling integration with external CRM, email marketing, or custom notification systems.
•	Cron Job for Abandoned Cart Detection: The plugin checks for abandoned carts every 5 minutes to ensure timely follow-ups.
•	Customizable Webhook URL: Easily configure the webhook URL in the settings page.
•	Seamless Integration with BotSailor: Use BotSailor to send automated WhatsApp messages to customers when an abandoned cart is detected.
________________________________________
How to Install the Plugin
1. Download the Plugin
First, download the plugin file (in .zip format) from the plugin repository or the developer's website.
2. Upload to WooCommerce
1.	Log in to your WordPress Admin Dashboard.
2.	Go to Plugins > Add New.
3.	Click on the Upload Plugin button at the top.
4.	Choose the .zip file you downloaded and click Install Now.
3. Activate the Plugin
Once installed, click on the Activate Plugin button to activate it.
________________________________________
Configuring the Plugin
1. Access the Plugin Settings
After activating the plugin:
1.	Navigate to Settings > Abandoned Cart Webhook in your WordPress Admin dashboard.
2.	You'll find the settings page to configure the webhook URL.
2. Set the Webhook URL
1.	In the Webhook URL field, enter the URL where you want to send the cart data when a cart is abandoned.
o	This URL will receive POST requests with cart details in JSON format.
2.	Once the URL is set, click Save Changes.
________________________________________
Integration with BotSailor for WhatsApp Notifications
What is BotSailor?
BotSailor is a powerful chatbot platform that allows businesses to automate communication with customers via WhatsApp and other messaging platforms. With BotSailor's webhook workflow, you can send personalized WhatsApp messages to customers who have abandoned their carts, encouraging them to complete their purchases.
How to Integrate with BotSailor
Step 1: Sign Up for BotSailor
1.	Visit the BotSailor website.
2.	Sign up for an account or log in if you already have one.
Step 2: Create a New Chatbot Workflow
1.	In your BotSailor dashboard, navigate to the Workflows section.
2.	Click on Create New Workflow.
3.	Choose Webhook as the trigger for the workflow.
Step 3: Configure the Webhook in BotSailor
1.	BotSailor will provide you with a unique Webhook URL.
2.	Copy this URL; you'll need it to configure the WooCommerce plugin.
Step 4: Set the Webhook URL in WooCommerce Plugin
1.	Return to Settings > Abandoned Cart Webhook in your WordPress Admin dashboard.
2.	Paste the BotSailor Webhook URL into the Webhook URL field.
3.	Click Save Changes.
Step 5: Design Your WhatsApp Message in BotSailor
1.	In the BotSailor workflow editor, design the message you want to send to customers.
o	Include variables like customer name, cart items, and a link to their cart.
2.	Set up any additional conditions or actions as needed.
Step 6: Activate the Workflow
1.	Once your message and workflow are set up, activate the workflow.
2.	BotSailor will now listen for incoming webhook data from your WooCommerce plugin.
How It Works
•	When a customer abandons their cart, the WooCommerce plugin sends the cart data to the BotSailor webhook URL.
•	BotSailor receives the data and triggers the workflow you've set up.
•	An automated WhatsApp message is sent to the customer, reminding them of their abandoned cart and encouraging them to complete the purchase.
________________________________________
How to Use the Plugin
1. Automatic Cart Data Capture
•	The plugin automatically captures customer and cart data when a product is added to the cart.
•	It tracks the following details:
o	Customer email
o	Phone number
o	Cart contents (products, quantities, etc.)
o	Billing and shipping address
•	If the customer leaves the cart without completing the purchase within 1 hour, the plugin sends the captured data to the configured webhook URL (e.g., BotSailor).
2. Testing the Webhook with BotSailor
You can manually send a sample webhook to test your BotSailor integration:
1.	Navigate to the Settings page in your WordPress admin.
2.	Click on the Send Sample Webhook button to generate a sample cart and send it to BotSailor.
3.	In BotSailor, check if the workflow was triggered and the WhatsApp message was sent.
