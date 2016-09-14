<?php
class Onepage_Customizer {
    public static function Onepage_Register($wp_customize) {
        self::Onepage_Sections($wp_customize);
        self::Onepage_Controls($wp_customize);
    }
    public static function Onepage_Sections($wp_customize) {
        /**
         * General Section
         */
        $wp_customize->add_section('general_setting_section', array(
            'title' => __('General Settings', 'one-page'),
            'description' => __('Allows you to Contact Info, Menu Text etc settings for Onepage Theme.', 'one-page'), //Descriptive tooltip
            'panel' => '',
            'priority' => '10',
            'capability' => 'edit_theme_options'
            )
        );
        /**
         * Home Page Top Feature Area
         */
        $wp_customize->add_section('home_top_feature_area', array(
            'title' => __('Top Feature Area', 'one-page'),
            'description' => __('Allows you to setup Top feature area section for Onepage Theme.', 'one-page'), //Descriptive tooltip
            'panel' => '',
            'priority' => '11',
            'capability' => 'edit_theme_options'
                )
        );
        /**
         * Home Page Feature Area
         */
        $wp_customize->add_section('home_page_feature_area', array(
            'title' => __('Home Page Feature Area', 'one-page'),
            'description' => __('Allows you to home page feature area section for Onepage Theme.', 'one-page'), //Descriptive tooltip
            'panel' => '',
            'priority' => '12',
            'capability' => 'edit_theme_options'
                )
        );
        /**
         * Home Page Service Section Area
         */
        $wp_customize->add_panel('home_page_service_panel', array(
            'title' => __('Home Page Service Section Area', 'one-page'),
            'description' => __('Allows you to home page service section for Onepage Theme.', 'one-page'), //Descriptive tooltip
            'panel' => '',
            'priority' => '13',
            'capability' => 'edit_theme_options'
                )
        );
        /**
         * Home Page Service Main Heading
         */
        $wp_customize->add_section('home_page_service_main_head', array(
            'title' => __('Home Page Service Main Heading', 'one-page'),
            'description' => __('Allows you to setup home page service area main head for Onepage Theme.', 'one-page'),
            'panel' => 'home_page_service_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
            )
        );
        /**
         * Home Page Service box 1
         */
        $wp_customize->add_section('home_page_first_service_box', array(
            'title' => __('Home Page First Service Box', 'one-page'),
            'description' => __('Allows you to setup home page service box for Onepage Theme.', 'one-page'),
            'panel' => 'home_page_service_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
            )
        );
        /**
         * Home Page Service box 2
         */
        $wp_customize->add_section('home_page_second_service_box', array(
            'title' => __('Home Page Second Service Box', 'one-page'),
            'description' => __('Allows you to setup home page service box for Onepage Theme.', 'one-page'),
            'panel' => 'home_page_service_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
            )
        );
        /**
         * Home Page Service box 3
         */
        $wp_customize->add_section('home_page_third_service_box', array(
            'title' => __('Home Page Third Service Box', 'one-page'),
            'description' => __('Allows you to setup home page service box for Onepage Theme.', 'one-page'),
            'panel' => 'home_page_service_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
            )
        );
        /**
         * Home Page Service box 4
         */
        $wp_customize->add_section('home_page_fourth_service_box', array(
            'title' => __('Home Page Fourth Service Box', 'one-page'),
            'description' => __('Allows you to setup home page service box for Onepage Theme.', 'one-page'),
            'panel' => 'home_page_service_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
            )
        );
        /**
         * Home Page Blog Section
         */
        $wp_customize->add_section('home_page_blog_area', array(
            'title' => __('Home Page Blog Section', 'one-page'),
            'description' => __('Allows you to setup home page blog area section for Onepage Theme.', 'one-page'),
            'panel' => '',
            'priority' => '14',
            'capability' => 'edit_theme_options'
                )
        );
        /**
         * Home Page Contact Section
         */
        $wp_customize->add_section('home_page_contact_area', array(
            'title' => __('Home Page Contact Area', 'one-page'),
            'description' => __('Allows you to setup contact information on home page for Onepage Theme.', 'one-page'),
            'panel' => '',
            'priority' => '15',
            'capability' => 'edit_theme_options'
            )
        );
         $wp_customize->remove_section("colors");
    }
    public static function Onepage_Section_Content() {
        $section_content = array(
            'general_setting_section' => array(
                'onepage_logo',
                'onepage_favicon',
                'onepage_contact_number',
                'onepage_nav',
                'onepage_opt_menu_heading',
                'onepage_opt_menu_link'
            ),
            'home_top_feature_area' => array(
                'onepage_slideimage1',
                'onepage_sliderheading1',
                'onepage_Sliderlink1',
                'onepage_sliderdes1'
            ),          
             'home_page_feature_area' => array(
                'onepage_page_main_heading',
                 'onepage_page_sub_heading'
            ),
            'home_page_service_main_head' => array(
                'onepage_our_services_heading'
             ),
            'home_page_first_service_box' => array(
                'onepage_our_services_image1',
                'onepage_our_services_title1',
                'onepage_services_title_link1',
                'onepage_our_services_desc1'
             ),
            'home_page_second_service_box' => array(
                'onepage_our_services_image2',
                'onepage_our_services_title2',
                'onepage_services_title_link2',
                'onepage_our_services_desc2'
             ),
            'home_page_third_service_box' => array(
                'onepage_our_services_image3',
                'onepage_our_services_title3',
                'onepage_services_title_link3',
                'onepage_our_services_desc3'
             ),
            'home_page_fourth_service_box' => array(
                'onepage_our_services_image4',
                'onepage_our_services_title4',
                'onepage_services_title_link4',
                'onepage_our_services_desc4'
             ),
            'home_page_blog_area' => array(
                'onepage_recent_blog_heading'
             ),
            'home_page_contact_area' => array(
                'onepage_our_contact_heading',
                'onepage_our_contact_sub_heading',
                'onepage_contact_address',
                'onepage_contact_phone_no',
                'onepage_ontact_email',
                'onepage_contact_website'
             )
        );
        return $section_content;
    }

    public static function Onepage_Settings() {
        $onepage_settings = array(
            'onepage_logo' => array(
                'id' => 'onepage_options[onepage_logo]',
                'label' => __('Custom Logo', 'one-page'),
                'description' => __('Upload a logo for your Website. The recommended size for the logo is 250px width x 50px height.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => get_template_directory_uri() . '/images/logo.png'
            ),
            'onepage_favicon' => array(
                'id' => 'onepage_options[onepage_favicon]',
                'label' => __('Custom Favicon', 'one-page'),
                'description' => __('Here you can upload a Favicon for your Website. Specified size is 16px x 16px.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'onepage_contact_number' => array(
                'id' => 'onepage_options[onepage_contact_number]',
                'label' => __('Contact Number', 'one-page'),
                'description' => __('Mention your contact number here through which users can interact to you directly.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => 'Call us on 2514578498'
            ), 
            'onepage_nav' => array(
                'id' => 'onepage_options[onepage_nav]',
                'label' => __('Mobile Navigation Menu', 'one-page'),
                'description' => __('Enter your mobile navigation menu text.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => 'Menu'
            ), 
            'onepage_opt_menu_heading' => array(
                'id' => 'onepage_options[onepage_opt_menu_heading]',
                'label' => __('Optional Menu Title', 'one-page'),
                'description' => __('Here you can create a page menu that will display in menu section on your featured homepage besides the default featured home page menu. It is optional.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => ''
            ), 
            'onepage_opt_menu_link' => array(
                'id' => 'onepage_options[onepage_opt_menu_link]',
                'label' => __('Optional Menu Link', 'one-page'),
                'description' => __('Here you can mention a URL for the optional menu that will redirect the users to the a particular page.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ), 
            //  Home Page Top Feature
            'onepage_slideimage1' => array(
                'id' => 'onepage_options[onepage_slideimage1]',
                'label' => __('Top Feature Image', 'one-page'),
                'description' => __('The optimal size of the image is 1600px wide x 750px height, but it can be varied as per your requirement.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => get_template_directory_uri() . '/images/1.jpg'
            ),
            'onepage_sliderheading1' => array(
                'id' => 'onepage_options[onepage_sliderheading1]',
                'label' => __('Top Feature Heading', 'one-page'),
                'description' => __('Mention the heading for the Top Feature.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('A bene placito.', 'one-page')
            ),
            'onepage_Sliderlink1' => array(
                'id' => 'onepage_options[onepage_Sliderlink1]',
                'label' => __('Link for Top Feature Image', 'one-page'),
                'description' => __('Mention the URL for first image.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ),
            'onepage_sliderdes1' => array(
                'id' => 'onepage_options[onepage_sliderdes1]',
                'label' => __('Link Text for Top Feature', 'one-page'),
                'description' => __('Mention the link text for top Feature Image', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => __('You have just dined, and however scrupulously the slaughterhouse is concealed in the graceful distance of miles, there is complicity.', 'one-page')
            ),       
            'onepage_page_main_heading' => array(
                'id' => 'onepage_options[onepage_page_main_heading]',
                'label' => __('Featured Heading', 'one-page'),
                'description' => __('Mention the punch line for your business here.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Premium WordPress Themes with Single Click Installation', 'one-page')
            ), 
            'onepage_page_sub_heading' => array(
                'id' => 'onepage_options[onepage_page_sub_heading]',
                'label' => __('Featured Sub Heading', 'one-page'),
                'description' => __('Mention the tagline for your business here that will complement the punch line.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Just a Click and your website is ready for use. Your Site is faster to built, easy to use & Search Engine Optimized.', 'one-page')
            ), 
             'onepage_our_services_heading' => array(
                'id' => 'onepage_options[onepage_our_services_heading]',
                'label' => __('Heading', 'one-page'),
                'description' => __('Here you can mention a suitable heading for your services. It will also appear on the home page menu.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Our Services', 'one-page')
            ), 
            //  Firsr Service Block
            'onepage_our_services_image1' => array(
                'id' => 'onepage_options[onepage_our_services_image1]',
                'label' => __('First Image', 'one-page'),
                'description' => __('The optimal size of the image is 264 px wide x 264 px height, but it can be varied as per your requirement.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => get_template_directory_uri() . '/images/circle_img1.jpg'
            ),
            'onepage_our_services_title1' => array(
                'id' => 'onepage_options[onepage_our_services_title1]',
                'label' => __('Title 1', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the title in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Single Click Installation', 'one-page')
            ),
            'onepage_services_title_link1' => array(
                'id' => 'onepage_options[onepage_services_title_link1]',
                'label' => __('Title 1', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the title in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ), 
            'onepage_our_services_desc1' => array(
                'id' => 'onepage_options[onepage_our_services_desc1]',
                'label' => __('Description 1', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the small description in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Just a Click and your website is ready for use. Your Site is faster to built, easy to use & Search Engine Optimized.', 'one-page')
            ),
            //  Second Service Block
            'onepage_our_services_image2' => array(
                'id' => 'onepage_options[onepage_our_services_image2]',
                'label' => __('Second Image', 'one-page'),
                'description' => __('The optimal size of the image is 264 px wide x 264 px height, but it can be varied as per your requirement.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => get_template_directory_uri() . '/images/circle_img2.jpg'
            ),
            'onepage_our_services_title2' => array(
                'id' => 'onepage_options[onepage_our_services_title2]',
                'label' => __('Title 2', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the title in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Single Click Installation', 'one-page')
            ),
            'onepage_services_title_link2' => array(
                'id' => 'onepage_options[onepage_services_title_link2]',
                'label' => __('Link for Title 2', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the title in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ), 
            'onepage_our_services_desc2' => array(
                'id' => 'onepage_options[onepage_our_services_desc2]',
                'label' => __('Description 2', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the small description in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Just a Click and your website is ready for use. Your Site is faster to built, easy to use & Search Engine Optimized.', 'one-page')
            ),
            //  Third Service Block
            'onepage_our_services_image3' => array(
                'id' => 'onepage_options[onepage_our_services_image3]',
                'label' => __('Third Image', 'one-page'),
                'description' => __('The optimal size of the image is 264 px wide x 264 px height, but it can be varied as per your requirement.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => get_template_directory_uri() . '/images/circle_img3.jpg'
            ),
            'onepage_our_services_title3' => array(
                'id' => 'onepage_options[onepage_our_services_title3]',
                'label' => __('Title 3', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the title in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Single Click Installation', 'one-page')
            ),
            'onepage_services_title_link3' => array(
                'id' => 'onepage_options[onepage_services_title_link3]',
                'label' => __('Link for Title 3', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the title in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ), 
            'onepage_our_services_desc3' => array(
                'id' => 'onepage_options[onepage_our_services_desc3]',
                'label' => __('Description 3', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the small description in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Just a Click and your website is ready for use. Your Site is faster to built, easy to use & Search Engine Optimized.', 'one-page')
            ),
            //  Fourth Service Block
            'onepage_our_services_image4' => array(
                'id' => 'onepage_options[onepage_our_services_image4]',
                'label' => __('Fourth Image', 'one-page'),
                'description' => __('The optimal size of the image is 264 px wide x 264 px height, but it can be varied as per your requirement.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => get_template_directory_uri() . '/images/circle_img4.jpg'
            ),
            'onepage_our_services_title4' => array(
                'id' => 'onepage_options[onepage_our_services_title4]',
                'label' => __('Title 4', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the title in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Single Click Installation', 'one-page')
            ),
            'onepage_services_title_link4' => array(
                'id' => 'onepage_options[onepage_services_title_link4]',
                'label' => __('Link for Title 4', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the title in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ), 
            'onepage_our_services_desc4' => array(
                'id' => 'onepage_options[onepage_our_services_desc4]',
                'label' => __('Description 4', 'one-page'),
                'description' => __('Here you can mention a suitable title that will display the small description in services section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Just a Click and your website is ready for use. Your Site is faster to built, easy to use & Search Engine Optimized.', 'one-page')
            ),
            // Home Page Blog Section
             'onepage_recent_blog_heading' => array(
                'id' => 'onepage_options[onepage_recent_blog_heading]',
                'label' => __('Blog Heading', 'one-page'),
                'description' => __('Here you can mention a suitable heading that will display as blog heading on home page. Also display on the menu.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => __('Recent Post', 'one-page')
            ),
            // Home Page Contact Section
            'onepage_our_contact_heading' => array(
                'id' => 'onepage_options[onepage_our_contact_heading]',
                'label' => __('Contact Sub Heading', 'one-page'),
                'description' => __('Here you can mention a suitable heading that will display as contact heading on the right side of home page under contact section.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => __('Contact', 'one-page')
            ),
            'onepage_our_contact_sub_heading' => array(
                'id' => 'onepage_options[onepage_our_contact_sub_heading]',
                'label' => __('Contact Heading', 'one-page'),
                'description' => __('Here you can mention a suitable heading that will display as your contact title on home page. Also display on the menu.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => __('Address', 'one-page')
            ),
            'onepage_contact_address' => array(
                'id' => 'onepage_options[onepage_contact_address]',
                'label' => __('Business Address', 'one-page'),
                'description' => __('Here you can put your business address that will display on home page of your website.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => __('Address -  The Address', 'one-page')
            ),
            'onepage_contact_phone_no' => array(
                'id' => 'onepage_options[onepage_contact_phone_no]',
                'label' => __('Contact Number', 'one-page'),
                'description' => __('Here you can mention your contact number that will appear on home page.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => __('Phone - 1245 213 689', 'one-page')
            ),
            'onepage_ontact_email' => array(
                'id' => 'onepage_options[onepage_ontact_email]',
                'label' => __('Contact Email', 'one-page'),
                'description' => __('Here you can mention your email id that will appear on home page.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => __('Email - name@example.com', 'one-page')
            ),
            'onepage_contact_website' => array(
                'id' => 'onepage_options[onepage_contact_website]',
                'label' => __('Your Website', 'one-page'),
                'description' => __('Here you can mention your website name that will appear on home page.', 'one-page'),
                'type' => 'option',
                'setting_type' => 'text',
                'default' => __('Website - www.example.com', 'one-page')
            ),
         );
        return $onepage_settings;
    }
    public static function Onepage_Controls($wp_customize) {
        $sections = self::Onepage_Section_Content();
        $settings = self::Onepage_Settings();
        foreach ($sections as $section_id => $section_content) {
            foreach ($section_content as $section_content_id) {
                switch ($settings[$section_content_id]['setting_type']) {
                    case 'image':
                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'onepage_sanitize_url');
                        $wp_customize->add_control(new WP_Customize_Image_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id']
                                )
                        ));
                        break;
                    case 'text':
                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'onepage_sanitize_text');
                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'text'
                                )
                        ));
                        break;
                    case 'textarea':
                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'onepage_sanitize_textarea');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'textarea'
                                )
                        ));
                        break;
                    case 'link':

                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'onepage_sanitize_url');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'text'
                                )
                        ));

                        break;
                    default:
                        break;
                }
            }
        }
    }
  public static function add_setting($wp_customize, $setting_id, $default, $type, $sanitize_callback) {
        $wp_customize->add_setting($setting_id, array(
            'default' => $default,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => array('Onepage_Customizer', $sanitize_callback),
            'type' => $type
                )
        );
    }
    /**
     * adds sanitization callback funtion : textarea
     * @package Onepage
     */
    public static function onepage_sanitize_textarea($value) {
        $value = esc_html($value);
        return $value;
    }
    /**
     * adds sanitization callback funtion : url
     * @package Onepage
     */
    public static function onepage_sanitize_url($value) {
        $value = esc_url($value);
        return $value;
    }
    /**
     * adds sanitization callback funtion : text
     * @package Onepage
     */
    public static function onepage_sanitize_text($value) {
        $value = sanitize_text_field($value);
        return $value;
    }
    /**
     * adds sanitization callback funtion : email
     * @package Onepage
     */
    public static function onepage_sanitize_email($value) {
        $value = sanitize_email($value);
        return $value;
    }
    /**
     * adds sanitization callback funtion : number
     * @package Onepage
     */
    public static function onepage_sanitize_number($value) {
        $value = preg_replace("/[^0-9+ ]/", "", $value);
        return $value;
    }
}
// Setup the Theme Customizer settings and controls...
add_action('customize_register', array('Onepage_Customizer', 'Onepage_Register'));
function inkthemes_registers() {
          wp_register_script( 'inkthemes_jquery_ui', '//code.jquery.com/ui/1.11.0/jquery-ui.js', array("jquery"), true  );
	wp_register_script( 'inkthemes_customizer_script', get_template_directory_uri() . '/js/inkthemes_customizer.js', array("jquery","inkthemes_jquery_ui"), true  );
	wp_enqueue_script( 'inkthemes_customizer_script' );
	wp_localize_script( 'inkthemes_customizer_script', 'ink_advert', array(
            'pro' => __('View PRO version','one-page'),
            'url' => esc_url('http://www.inkthemes.com/wp-themes/one-page-parallax-wordpress-theme/'),
			'support_text' => __('Need Help!','one-page'),
			'support_url' => esc_url('http://www.inkthemes.com/lets-connect/')
            )
            );
}
add_action( 'customize_controls_enqueue_scripts', 'inkthemes_registers' );
