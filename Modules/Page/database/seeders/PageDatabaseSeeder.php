<?php

namespace Modules\Page\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Modules\Page\Models\Page;
use Modules\MenuBuilder\Models\MenuBuilder;
use Illuminate\Support\Facades\Schema;



class PageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        $pages = [
            [
                'name' => 'Privacy Policy',
                'description' => 'This Privacy Policy is brought by Iqonic Design. Iqonic Design is the sole owner of a number of demo websites containing previews of WordPress website themes. This Privacy Policy shall apply to all Iqonic Design sites where this Privacy Policy is featured. This Privacy Policy describes how the Iqonic Design collects, uses, shares and secures personal information that you provide.

                                Iqonic Design does not share personal information of any kind with anyone. We will not sell or rent your name or personal information to any third party. We do not sell, rent or provide outside access to our mailing list or any data we store. Any data that a user stores via our facilities is wholly owned by that user or business. At any time, a user or business is free to take their data and leave, or to simply delete their data from our facilities.

                                Iqonic Design only collects specific personal information that is necessary for you to access and use our services. This personal information includes, but is not limited to, first and last name, email address, Country of residence.

                                Iqonic Design may release personal information if Iqonic Design is required to by law, search warrant, subpoena, court order or fraud investigation. We may also use personal information in a manner that does not identify you specifically nor allow you to be contacted but does identify certain criteria about our site’s users in general (such as we may inform third parties about the number of registered users, number of unique visitors, and the pages most frequently browsed).



                                Use of Information
                                We use the information to enable your use of the site and its features and to assure security of use and prevent any potential abuse. We may use the information that we collect for a variety of purposes including:
                                
                                Promotion — With your consent we send promotional communications, such as providing you with information about products and services, features, surveys, newsletters, offers, promotions, contests and events;
                                
                                Safety and security — We use the information we have to verify accounts and activities, combat harmful conduct, detect and prevent spam and other bad experiences, maintain the integrity of the Platform, and promote safety and security.
                                
                                Product research and development — We use the information we have to develop, test and improve our Platform and Services, by conducting surveys and research, and testing and troubleshooting new products and features.
                                
                                Communication with you — We use the information we have to send you various communications, communicate with you about our products, and let you know about our policies and terms. We also use your information to respond to you when you contact us.
                                
                                
                                
                                Amendments
                                We may amend this Privacy Policy from time to time. When we amend this Privacy Policy, we will update this page accordingly and require you to accept the amendments in order to be permitted to continue using our services.
                                
                                
                                Contact Us
                                You can learn more about how privacy works within our site by contacting us. If you have questions about this Policy, you can contact us via email address provided. Additionally, we may also resolve any disputes you have with us in connection with our privacy policies and practices through direct contact. Write to us at hello@iqonic.design',
            ],

            [
                'name' => 'Terms & Conditions',
                'description' => 'By accessing products on this site and placing an order from our website, you confirm that you are in agreement with and bound by the terms and conditions presented and outlined here. These terms apply to the entire website and any email or other type of communication between you and Iqonic Design. The Iqonic Design team is not liable for any direct, indirect, incidental or consequential damages, including, but not limited to, loss of data or profit, arising out of the use the materials on this site.

                                    Iqonic Design will not be responsible for any outcome that may occur during the course of usage of our resources. We reserve the rights to change prices and revise the resources usage policy in any moment.

                                    Products
                                    All products and services offered on this site are produced by Iqonic Design. You can access your download from your respective dashboard. We do not provide support for 3rd party software, plugins or libraries that you might have used with our products.

                                    Security
                                    Iqonic Design does not process any order payments through the website. All payments are processed securely through RazorPay and Stripe, a third party online payment providers.

                                    Cookie Policy
                                    A cookie is a file containing an identifier (a string of letters and numbers) that is sent by a web server to a web browser and is stored by the browser. The identifier is then sent back to the server each time the browser requests a page from the server. Our website uses cookies. By using our website and agreeing to this policy, you consent to our use of cookies in accordance with the terms of this policy.

                                    We use session cookies to personalize the website for each user.

                                    We use Google Analytics to analyze the use of our website. Our analytics service provider generates statistical and other information about website use by means of cookies. Deleting cookies will have a negative impact on the usability of the site. If you block cookies, you will not be able to use all the features on our website.


                                    Refunds
                                    You can ask for refund against the item purchased under certain circumstances listed in our Refund Policy. In the event that you meet the applicable mark for receiving refund, Iqonic Design will issue you a refund and ask you to specify how the product turned down your item performance expectations.

                                    Email
                                    By signing up on our website https://iqonic.design you agree to receive emails from us – Transactional as well as promotional (occasional).

                                    Ownership
                                    Ownership of the product is governed by the usage license.

                                    Changes about terms
                                    We may change/update our terms of use without any prior notice. If we change our terms and condition, we will post those changes on this page. Users can check latest version in here.',
            ],
            [
                'name' => 'Help and Support',
                'description' => 'Contact Us for Support <br>

                                    Email <br>

                                    Email us at : hello@iqonic.design <br>

                                    Telephone <br>

                                    Call us at : +1234567890 <br>

                                    Additional Information <br>

                                    If you need further assistance or have specific inquiries, feel free to reach out through the provided contact options.',
            ],
            [
                'name' => 'Refund and Cancellation Policy',
                'description' => 'At Health Wellness, we are committed to providing you with high-quality services that meet your needs and exceed your expectations. However, we understand that things do not always go according to plan, and sometimes cancellations or refunds might be necessary. This policy outlines the circumstances under which you can request a refund or cancel a booking made through the Health Wellness mobile app.

                                  Cancellations & :
                                              
                                  Unavailability of Health Wellness: You can cancel any booking free of charge until the provider accepts it. To cancel, open the Health Wellness Customer App, navigate to your booking details, and tap "Cancel Booking." If you cancel before the provider accepts your booking, you will receive a full refund of the service fee for advance booking services.
                                              
                                  Unavailability of Health Wellness: In the rare event that a Health Wellness is unable to make it to your scheduled appointment due to unforeseen circumstances, the provider can decline your service.
                                              
                                  Reschedule your appointment: We will work with you to find a new appointment time that suits your needs.
                                              
                                  Cancel your booking and receive a full refund: No cancellation fee will be applied (If this service is based on advance booking service).
                                              
                                  Refunds:
                                              
                                  Refunds will be processed into the users wallet if this service is based on Advance booking service.
                                              
                                  The following situations are not eligible for a full refund: 
                                  
                                  You cancel your booking before the provider accepts it.
                                  
                                  You are not satisfied with the quality of the work performed, but the Health Wellness completed the service as described in the app.
                                  
                                  You experience minor inconveniences or delays during the service.
                                  
                                  You request changes to the scope of the service after the Health Wellness has arrived, which may result in additional charges.
                                  
                                  Dispute Resolution:
                                  
                                  If you have any concerns or disagreements regarding your refund or cancellation request, please contact Health Wellness customer support at hello@iqonic.design or call us at +1234567890. We are committed to resolving all issues fairly and amicably.
                                  
                                  Important Notes: 
                                  
                                  This Refund and Cancellation Policy applies to all bookings made through the Health Wellness App or Website.
                                  
                                  Health Wellness reserves the right to modify this policy at any time without prior notice. The latest version of this policy will always be available on the Health Wellness website and mobile app.
                                  
                                  By using the Health Wellness mobile app, you agree to be bound by this Refund and Cancellation Policy.
                                  
                                  We hope this policy clarifies the process for requesting refunds and cancellations on the Health Wellness app. If you have any questions, please feel free to contact our customer support team.
                                  
 

                                  Sincerely,
                                                                  
                                  The Health Wellness Team',
            ],
            [
                'name' => 'Data Deletation Request',
                'description' => 'To ensure the safety and privacy of your data, we offer a streamlined process for 
                requesting its removal from our servers. To initiate the deletion process, kindly send an email from your registered email address to 
                our dedicated email inbox at hello@iqonic.design Upon based on your request, our team will thoroughly examine the provided details and proceed with necessary actions in adherence to our privacy policies and legal obligations.<br> 
                Sincerely, <br> 
                The Health Wellness Team',
            ]
        ];

        // Insert data into the pages table 
        foreach ($pages as $page) {
            Page::create($page);
        }

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();

    }

}

