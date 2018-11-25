<template>
    <div><!-- main container -->
        <div class="animated fadeIn"> <!-- main div -->
            <b-row> <!-- main row -->
                <b-col sm="12">
                    <b-card > <!-- main card -->
                        <h5 slot="header">  <!-- table header -->
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Department List
                                <small class="bfont-italic">List of all registered departments.</small></span>s
                        </h5>
                        
                        <b-row class="mb-2"> <!-- row button and search input -->
                            <b-col sm="4">
                                    <b-button variant="primary">
                                            <i class="fa fa-plus-circle"></i> Create New Department
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.departments.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row> <!-- row button and search input -->
                    
                        <b-row> <!-- row table -->
                            <b-col sm="12">
                                <b-table  
                                    :filter="filters.departments.criteria"
                                    :fields="tables.departments.fields"
                                    :items.sync="tables.departments.items"
                                    :current-page="paginations.departments.currentPage"
                                    :per-page="paginations.departments.perPage"
                                    striped hover small bordered show-empty
                                    
                                > <!-- table -->

                                <template slot="action" slot-scope="data"> <!-- action slot  :to="{path: 'categories/' + data.item.id } -->
                                    <b-btn variant="primary" @click="modalShow = !modalShow">
                                        <i class="fa fa-edit"></i>
                                    </b-btn>

                                    <b-btn variant="danger" @click="onItemDelete(data)">
                                        <i class="fa fa-trash"></i>
                                    </b-btn>
                                </template>

                                </b-table> <!-- table -->
                            </b-col>
                        </b-row> <!-- row table -->

                        <b-row >  <!-- Pagination -->
                                <b-col sm="12" class="my-1">
                                    <b-pagination size="sm" align="right" :total-rows="paginations.departments.totalRows" :per-page="paginations.departments.perPage" v-model="paginations.departments.currentPage"
                                    @change="getResults" class="my-0" />
                                </b-col>
                        </b-row> <!-- Pagination -->
                        
                    </b-card><!-- main card -->
                </b-col>
            </b-row> <!-- main row -->

            </div><!-- main div -->

            <div> <!-- modal div -->
                <b-modal id="add-edit-modal" title="Add/Edit Department" v-model="modalShow" >
                

                <div slot="modal-footer"><!-- modal footer buttons -->
                    <b-btn size="sm"  variant="secondary" @click="modalShow = false">
                        Cancel
                    </b-btn>

                    <b-btn size="sm"  variant="primary" @click="modalShow = false">
                        Save
                    </b-btn>
                </div> <!-- modal footer buttons -->

                </b-modal>
            </div> <!-- modal div -->

</div> <!-- main container -->

   
</template>

<script>
export default {
    name: 'departments',
    data () {
      return {
        tables: {
          departments: {
            fields: [
              {
                key: 'department_code',
                label: 'Code'
              },
              {
                key: 'department_desc',
                label: 'Department Name'
              },
              {
                 
                key: 'action',
                label: '',
                thStyle: {width: '100px'}
              },
            ],
            items: []
          }
        },
        filters: {
          departments: {
            criteria: null
          }
        },
        paginations: {
          departments: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        modalShow: false
      }
    },
    methods:{
        clearFilter(){
            this.filters.departments.criteria = null;
            //alert("aa");
        },
        // Our method to GET results from a Laravel endpoint
		getResults(page) {
			if (typeof page === 'undefined') {
				page = 1;
			}

			// Using vue-resource as an example
			// this.$http.get('api/departments?page=' + page)
			// 	.then(response => {
            //         this.tables.departments.items = response.data.data
            //         this.paginations.departments.currentPage = response.data.meta.current_page
            // 	});
            

            // ali ya ma rerender keng current page
            this.$http.get('api/departments?page=' + page)
            .then(response => {
                    const records = response.data
                    this.paginations.departments.currentPage = records.meta.current_page
                    this.tables.departments.items = records.data
                    
            	}).catch(error => {
                    console.log(error.response.data);
                });

                //this.$emit("tb1").reload();

		}
    },
    computed: {

    },
    created () {
      this.fillTableList('departments')
      console.log(this.tables.departments.items);
    }
  }
</script>

