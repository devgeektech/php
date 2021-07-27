<?php
    class CategoriesMappingPanelBuilder extends IeProProfileObject {
        private $defaultCategory;
        private $allCategories;
        private $categoryMappings;

        public function __construct( $controller) {
            parent::__construct( $controller);
        }

        public function setDefaultCategory( $defaultCategory) {
            $this->defaultCategory = $defaultCategory;
        }

        public function setAllCategories( $allCategories) {
            $this->allCategories = $allCategories;

            $this->build_categories_map();
        }

        public function setCategoryMappings( $categoryMappings) {
            $this->categoryMappings = $categoryMappings;
        }

        public function build() {
            $default_category_name = empty( $this->defaultCategory)
                                     ? 'None'
                                     : $this->get_category_name( $this->defaultCategory);

            $result = '<div class="row">
                        <div class="col-md-2 text-right">
                            <strong>' . $this->language->get( 'profile_import_categories_default_label') . '</strong>:
                        </div>

                        <div class="col-md-10">
                            <input class="form-control category_input_selector"
                                   value="' . $default_category_name . '">
                            <input type="hidden" name="categories_mapping_default"
                                   value="' . $this->defaultCategory . '">
                        </div>
                    </div>

                    <div style="clear: both; height: 20px;"></div>';

            $result .= $this->build_categories_table();

            return $result;
        }

        private function build_categories_table() {
            $result = '<table class="table table-bordered table-hover">
                         <thead>
                             <tr>
                                 <td>' . $this->language->get( 'profile_import_categories_user_category') . '</td>
                                 <td>' . $this->language->get( 'profile_import_categories_opencart_category') . '</td>
                             </tr>
                         <thead>

                         <tbody>';

            $index = 0;

            if ($this->is_object_mappings()) {
                foreach ($this->categoryMappings as $cat_name => $category) {
                    $hiddenFieldName = "categories_mapping[{$index}]";

                    if (!empty( $category->segments)){
                        $hiddenValue = implode( ',', $category->segments);
                    }
                    else {
                        $hiddenValue = !empty($category->name) ? $category->name : '';
                    }

                    $result .= '<tr>';
                    $result .= '<td>';
                    $categoryName = !empty($category->name) ? $category->name : $cat_name;

                    $result .= $categoryName;
                    $result .= "<input type=\"hidden\" name=\"{$hiddenFieldName}\"
                                    value=\"{$hiddenValue}\"";

                    $result .= '</td>';

                    $result .= '<td>';
                    $result .= "<input class=\"form-control category_input_selector\"
                                       value=\"None\">";
                    $result .= "<input type=\"hidden\"
                                       name=\"categories_mapping_opencart[{$index}]\"
                                       value=\"\">";

                    $result .= '</td>';
                    $result .= '</tr>';

                    $index++;
                }
            } else {
                foreach ($this->categoryMappings as $userCategoryName => $categoryId) {
                    $hiddenFieldName = "categories_mapping[{$index}]";

                    $categoryName = $userCategoryName;
                    $userCategoryDisplayName = preg_replace( '/,/', '&nbsp;&gt;&nbsp;', $categoryName); //$categoryId['name'];
                    $hiddenValue = $categoryName;

                    $result .= '<tr>';

                    $result .= '<td>';
                    $result .= $userCategoryDisplayName;
                    $result .= "<input type=\"hidden\" name=\"{$hiddenFieldName}\"
                                       value=\"{$hiddenValue}\"";
                    $result .= '</td>';

                    $result .= '<td>';
                    $categoryName = $this->get_category_name( $categoryId);

                    $result .= "<input class=\"form-control category_input_selector\"
                                       value=\"{$categoryName}\">";
                    $result .= "<input type=\"hidden\"
                                       name=\"categories_mapping_opencart[{$index}]\"
                                       value=\"{$categoryId}\">";
                    $result .= '</td>';

                    $result .= '</tr>';

                    $index++;
                }
            }

            $result .= '  </tbody>
                      </table>';

            return $result;
        }

        private function is_object_mappings() {
            $keys = array_keys( $this->categoryMappings);

            return !is_string( $keys[0]);
        }

        private function get_category_name( $category_id) {
            return isset( $this->categories_map[$category_id])
                   ? $this->categories_map[$category_id]['name']
                   : 'None';
        }

        private function build_categories_map() {
            $this->categories_map = [];

            foreach ($this->allCategories as $category) {
                $this->categories_map[$category['category_id']] = $category;
            }
        }
    }
