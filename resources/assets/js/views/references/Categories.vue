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
                                    <b-button v-if="checkRights('4-14')" variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('category')">
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
                                    v-if="checkAction"
                                    responsive
                                    :filter="filters.categories.criteria"
                                    :fields="tables.categories.fields"
                                    :items.sync="tables.categories.items"
                                    :current-page="paginations.categories.currentPage"
                                    :per-page="paginations.categories.perPage"
                                    @filtered="onFiltered($event,'categories')"
                                    striped hover small bordered show-empty
                                >
                                    <template slot="action" slot-scope="data">
                                        <b-btn v-if="checkRights('4-15')" :size="'sm'" variant="primary" @click="setUpdate(data)">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn v-if="checkRights('4-16')" :size="'sm'" variant="danger" @click="setDelete(data)">
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
            @shown="focusElement('category_code')"
        >
            <div slot="modal-title">
                Category Entry - {{entryMode}}
            </div>
            <b-col lg=12>
                <b-form @keydown="resetFieldStates('category')" autocomplete="off">
                    <b-form-group>
                        <label for="category_code">* Category Code</label>
                        <b-form-input
                            ref="category_code"
                            id="category_code"
                            v-model="forms.category.fields.category_code"
                            type="text"
                            placeholder="Category Code">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label>* Category Desc</label>
                        <b-form-input
                            ref="category_desc"
                            id="category_desc"
                            v-model="forms.category.fields.category_desc"
                            type="text"
                            placeholder="Category Description">
                        </b-form-input>
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
                    }
                }
            },
            tables: {
                categories: {
                    fields:[
                        {
                            key:'category_code',
                            label: 'Category Code',
                            thStyle: {width: '150px'},
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'category_desc',
                            label: 'Description',
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'action',
                            label:'',
                            thStyle: {width: '75px'},
                            tdClass: 'text-center',
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
            category_id: null,
            row: []
        }
    },
    methods:{
        onCategoryEntry () {
            if(this.entryMode == 'Add'){
                
                this.createEntity('category', true, 'categories')
            }
            else{
                this.updateEntity('category', 'category_id', true, this.row)
            }
        },
        onCategoryDelete(){
            this.deleteEntity('category', this.category_id, true, 'categories', 'category_id')
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
            this.row = data.item
            this.fillEntityForm('category', data.item.category_id)
            this.showModalEntry=true
            this.entryMode='Edit'

        }
    },
    computed: {
        checkAction(){
            if(this.$store.state.rights.length > 0){
                if((this.checkRights('4-15') || this.checkRights('4-16')) == false){
                    this.tables.categories.fields.pop()
                }
            }
            return true
        }
    },
    created () {
        this.fillTableList('categories');
    },
  }
</script>

