<?PHP
class ACF_Custom_Button_Field extends acf_field {
    public function __construct() {
        $this->name = 'custom_button';
        $this->label = __('Custom Button', 'acf');
        $this->category = 'basic';
        $this->defaults = array(
            'button_text' => 'Click Me', // Default button text
            'button_css_class' => 'acf-custom-button' // Default CSS class
        );

        parent::__construct();
    }

    public function render_field($field) {
        $post_id = get_the_ID(); // Get current post ID
        // Render the button with the label
        $button_text = esc_html($field['button_text']);
        $button_css = esc_attr($field['button_css_class']);
        // echo '<button type="button" class="' . $button_css . '">' . $button_text . '</button>';
        echo '<button type="button" class="acf-custom-button" data-post-id="' . $post_id . '">' . esc_html($field['button_text']) . '</button>';
    }

    public function render_field_settings($field) {
        // Add setting for Button Text
        acf_render_field_setting($field, array(
            'label'        => __('Button Text', 'acf'),
            'instructions' => __('Enter the text to display on the button'),
            'type'         => 'text',
            'name'         => 'button_text',
        ));

        // Add setting for CSS Class
        acf_render_field_setting($field, array(
            'label'        => __('CSS Class', 'acf'),
            'instructions' => __('Enter a CSS class to style the button'),
            'type'         => 'text',
            'name'         => 'button_css_class',
        ));
    }
}

new ACF_Custom_Button_Field();
