<style>
</style>

<template>
    <!--<b-animated fade-in>  main container -->
    <div>
        <div>
            <b-modal
                v-model="showModal"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
                @shown="focusElement('location_id')"
            >
                <div slot="modal-title">
                    Rental Rates and Charges
                </div>
                
                <b-row>
                    <b-col lg=12>
                        <b-form-group>
                            <label>Location</label>
                            <select2
                                ref="location_id"
                                :allowClear="false"
                                :placeholder="'Select Location'"
                                v-model="location_id"
                            >
                                <option value="0">All</option>
                                <option v-for="location in options.locations.items" :key="location.location_id" :value="location.location_id">{{  location.location_desc }}</option>
                            </select2>
                        </b-form-group>
                        <b-form-group>
                            <label>App Year</label>
                            <date-picker 
                                ref="app_year"
                                id="app_year"
                                v-model="app_year"
                                lang="en" 
                                input-class="form-control mx-input"
                                format="YYYY"
                                type="year"
                                :clearable="false">
                            </date-picker>
                        </b-form-group>
                        <b-form-group>
                            <label>Month</label>
                            <select2
                                ref="month_id"
                                :allowClear="false"
                                :placeholder="'Select Month'"
                                v-model="month_id"
                            >
                                <option v-for="month in options.months.items" :key="month.month_id" :value="month.month_id">{{  month.month_name }}</option>
                            </select2>
                        </b-form-group>
                        <b-form-group>
                            <label>Charge Type</label>
                            <select2
                                ref="charge_type"
                                :allowClear="false"
                                :placeholder="'Select Charge Type'"
                                v-model="charge_type"
                            >
                                <option value="0">All</option>
                                <option value="1">Utilities</option>
                                <option value="2">Miscellaneous</option>
                                <option value="3">Other</option>
                            </select2>
                        </b-form-group>
                    </b-col>
                </b-row>
                <div slot="modal-footer">
                    <b-btn
                        variant="success"
                        @click="fetchData()"
                    >
                        <i class="fa fa-file-excel-o"></i> 
                        Download Data
                    </b-btn>
                    <b-button variant="secondary" @click="showModal=false">Close</b-button>            
                </div>
            </b-modal>
        </div>
    </div>
</template>
<script>
export default {
    name: 'rentalAndCharges',
    data () {
        return {
            excel :{
                fields: {
                    'Billing No.': 'billing_no',
                    'Space Code': 'space_code',
                    'Area(Sqm)': 'number',
                    'Tenant': 'trade_name',
                    'Basic Rent': 'total_fixed_rent',
                    'Discounted Rent': 'total_discounted_rent',
                    'Basic Rent': 'total_fixed_rent'
                },
                data: [],
            },
            showModal: true,
            location_id: null,
            app_year: null,
            month_id: null,
            charge_type: null,
            options: {
                locations: {
                    items: []
                },
                months: {
                    items: []
                }
            },
        }
    },
    async created(){
        await this.fillOptionsList('locations')
        await this.fillOptionsList('months')
    },
    methods: {
        async fetchData(){
            var year = this.moment(this.app_year, 'YYYY')
            await this.$http.get('api/reports/rentalandcharges/' + this.location_id +'/'+year+'/'+ this.month_id+'/'+this.charge_type, {
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
                this.excel.data = response.data
                console.log(response)
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },
        previewReport(){
            if(this.location_id != null){
                let routeData = this.$router.resolve({name: 'Tenant Per Sqm Rate', query: {location_id: this.location_id}});
                window.open(routeData.href, '_blank');
            }
        },
    },
  }
</script>

