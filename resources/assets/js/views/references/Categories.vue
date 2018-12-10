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
                                    <b-button variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('category')">
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
                                        <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn :size="'sm'" variant="danger" @click="setDelete(data)">
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
                <b-form @keydown="resetFieldStates('category')">
                    <b-form-group>
                        <label for="category_code">* Category Code</label>
                        <b-form-input
                            id="category_code"
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
                    </b-form-group>
                    <b-form-group>
                        <label>* Category Desc</label>
                        <b-form-input
                            id="category_desc"
                            v-model="forms.category.fields.category_desc"
                            :state="forms.category.states.category_desc"
                            type="text"
                            placeholder="Category Description">
                        </b-form-input>
                        <b-form-invalid-feedback>
                            <i class="fa fa-exclamation-triangle text-danger"></i>
                            <span v-for="itemError in forms.category.errors.category_desc">
                                {{itemError}}
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-form>
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.category.isSaving" variant="primary" @click="onCategoryEntry">
                    <icon v-if="forms.category.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalEntry=false">Close</b-button>            
            </div>
        </b-modal>
        <b-modal 
            v-model="showModalDelete"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Delete Category
            </div>
            <b-col lg=12>
                Are you sure you want to delete this category?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.category.isSaving" variant="primary" @click="onCategoryDelete">
                    <icon v-if="forms.category.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    OK
                </b-button>
                <b-button variant="secondary" @click="showModalDelete=false">Close</b-button>            
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'categories',
    data () {
        return {
            entryMode: 'Add',
            showModalEntry: false, //if true show modal
            showModalDelete: false,
            forms: {
                category : {
                    isSaving: false,
                    fields: {
                        category_id: null,
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
                            label: 'Category Code',
                            thStyle: {width: '150px'}
                        },
                        {
                            key:'category_desc',
                            label: 'Description',
                        },
                        {
                            key:'action',
                            label:'',
                            thStyle: {width: '75px'}
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
            },
            category_id: null
        }
    },
    methods:{
        onCategoryEntry () {
            if(this.entryMode == 'Add'){
                
                this.createEntity('category', true, 'categories')
            }
            else{
                this.updateEntity('category', 'category_id', true, 'categories')
            }
        },
        onCategoryDelete(){
            this.deleteEntity('category', this.category_id, true, 'categories')
        },
        async setDelete(data){
            if(await this.checkIfUsed('category', data.item.category_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.category_id = data.item.category_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.fillEntityForm('category', data.item.category_id)
            this.showModalEntry=true
            this.entryMode='Edit'

        }
    },
    created () {
        this.fillTableList('categories');
    },
  }
</script>

