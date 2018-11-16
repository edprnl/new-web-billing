<template>
    <!--<b-animated fade-in>  main container -->
   <div class="animated fadeIn">
        <b-row>
            <b-col sm="12">
                <b-card>
                    <h5 slot="header">
                        <span class="text-primary">
                            <i class="fa fa-bars"></i> 
                            Tenant List
                            <small class="font-italic">List of all registered tenants.</small></span>
                    </h5>
                    <b-row class="mb-2">
                        <b-col  sm="4">
                                <b-button variant="primary">
                                        <i class="fa fa-plus-circle"></i> Create New Tenant
                                </b-button>
                        </b-col>

                        <b-col  sm="4">
                            <span></span>
                        </b-col>

                        <b-col  sm="4">
                            <b-form-input 
                                        v-model="filters.tenants.criteria" 
                                        type="text" 
                                        placeholder="Search">
                            </b-form-input>
                        </b-col>
                    </b-row>
                    
                    <b-row>
                        <b-col sm="12">
                            <b-table 
                                :filter="filters.tenants.criteria"
                                :fields="tables.tenants.fields"
                                :items.sync="tables.tenants.items"
                                :current-page="paginations.tenants.currentPage"
                                :per-page="paginations.tenants.perPage"
                                striped hover small bordered show-empty
                            >
                                <template slot="action" slot-scope="data">
                                    <b-btn :size="'sm'" variant="primary" :to="{path: 'categories/' + data.item.id }">
                                        <i class="fa fa-edit"></i>
                                    </b-btn>

                                    <b-btn :size="'sm'" variant="danger" @click="onItemDelete(data)">
                                        <i class="fa fa-trash"></i>
                                    </b-btn>
                                </template>
                                
                            </b-table>

                            <b-pagination
                                        :align="'right'"
                                        :total-rows="paginations.tenants.totalRows"
                                        :per-page="paginations.tenants.perPage"
                                        v-model="paginations.tenants.criteria" />
                        </b-col>
                    </b-row>

                </b-card>
            </b-col>
        </b-row>
   </div>
</template>

<script>
export default {
    name: 'tenants',
    data () {
      return {
          tables: {
              tenants: {
                  fields:[
                      {
                          key:'tenant_code',
                          label: 'Tenant Code'
                      },
                      {
                          key:'trade_name',
                          label: 'Trade Name'
                      },
                      {
                          key:'company_name',
                          label: 'Company Name'
                      },
                      {
                          key:'head_office_address',
                          label: 'Address'
                      },
                      {
                          key:'contact_person',
                          label: 'Contact Person'
                      },
                      {
                          key:'designation',
                          label: 'Designation'
                      },
                      {
                          key:'business_concept',
                          label: 'Business Concept'
                      },
                      {
                          key: 'action',
                          label: 'Action'
                      }
                  ],
                  items: []
                }
            },
            filters: {
                tenants: {
                    criteria: null
                }
            },
            paginations: {
                tenants: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
              }
            }
        }
    },
    methods:{
        // loadDepartments(){
        //    axios.get("api/departments").then(({ data }) => (this.departments = data));
        // }

    },
    created () {
      this.fillTableList('tenants');
    }
  }
</script>

