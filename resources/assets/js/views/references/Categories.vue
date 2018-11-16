<template>
    <!--<b-animated fade-in>  main container -->
    <div>
        <notifications group="notification" />
        <div class="animated fadeIn">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Category List
                                <small class="font-italic">List of all registered category.</small></span>
                        </h5>
                        <b-row class="mb-2">
                            <b-col sm="4">
                                    <b-button variant="primary" @click="showModalEntry = true">
                                            <i class="fa fa-plus-circle"></i> Create New Category
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.categories.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col sm="12">
                                <b-table 
                                    :filter="filters.categories.criteria"
                                    :fields="tables.categories.fields"
                                    :items.sync="tables.categories.items"
                                    :current-page="paginations.categories.currentPage"
                                    :per-page="paginations.categories.perPage"
                                    striped hover small bordered show-empty
                                >
                                    <template slot="action" slot-scope="data">
                                        <b-btn :size="'sm'" variant="primary" :to="{path: 'category/' + data.item.id }">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn :size="'sm'" variant="danger" @click="onItemDelete(data)">
                                            <i class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                                    
                                </b-table>

                                <b-pagination
                                            :align="'right'"
                                            :total-rows="paginations.categories.totalRows"
                                            :per-page="paginations.categories.perPage"
                                            v-model="paginations.categories.currentPage" />
                            </b-col>
                        </b-row>

                    </b-card>
                </b-col>
            </b-row>
        </div>
        <b-modal 
            v-model="showModalEntry"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Category Entry - {{entryMode}}
            </div>
            <b-col lg=12>
                <b-form-group>
                    <label for="category_code">* Category Code</label>
                    <b-input-group>
                        <b-form-input
                            v-model="forms.category.fields.category_code"
                            :state="forms.category.states.category_code"
                            type="text"
                            placeholder="Category Code">
                        </b-form-input>
                        <b-form-invalid-feedback>
                            <i class="fa fa-exclamation-triangle text-danger"></i>
                            <span v-for="itemError in forms.category.errors.category_code">
                                {{itemError}}
                            </span>
                        </b-form-invalid-feedback>
                    </b-input-group>
                </b-form-group>
                <b-form-group>
                    <label>* Category Desc</label>
                    <b-form-input
                        v-model="forms.category.fields.category_desc"
                        id="category_desc"
                        type="text"
                        placeholder="Category Desc">
                    </b-form-input>
                    <b-form-invalid-feedback>
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                        <span v-for="itemError in forms.category.errors.category_desc">
                            {{itemError}}
                        </span>
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.category.isSaving" variant="primary" @click="onCreateCategory">
                    <icon v-if="forms.category.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalEntry=false">Close</b-button>            
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'departments',
    data () {
        return {
            entryMode: 'Add',
            showModalEntry: false,
            showModalDelete: false,
            forms: {
                category: {
                    isSaving: false,
                    fields: {
                        category_code: null,
                        category_desc: null
                    },
                    states: {
                        category_code: null,
                        category_desc: null
                    },
                    errors: {
                        category_code: null,
                        category_desc: null
                    }
                }
            },
            tables: {
                categories: {
                    fields:[
                        {
                            key:'category_code',
                            label: 'Category Code'
                        },
                        {
                            key:'category_desc',
                            label: 'Description'
                        },
                        {
                            key:'action',
                            label:'Action'
                        }
                    ],
                    items: []
                }
            },
            filters: {
                categories: {
                    criteria: null
                }
            },
            paginations: {
                categories: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            }
        }
    },
    methods:{
        onCreateCategory () {
            this.createEntity('category')
            // this.showModalEntry=false
        }
    },
    created () {
      this.fillTableList('categories');
    }
  }
</script>

