<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from "@/Layouts/main.vue";
import PageHeader from "@/Components/page-header.vue";
import Slider from "@vueform/slider";
import Multiselect from "@vueform/multiselect";
import Swal from "sweetalert2";
import { ref, watch } from "vue";
import "@vueform/multiselect/themes/default.css";
import animationData from "@/Components/widgets/msoeawqm.json";
import Lottie from "@/Components/widgets/lottie.vue";
import { productsData } from "@/common/data"

export default {
    setup(){
        let ingredientData=ref([])
        const getIngredient=async ()=> {
            try {
                const response = await axios.get('/api/ingredient/list');
                const data = response.data;

                console.log('list',data);

                ingredientData.value=data.data;
            } catch (error) {
                console.error('error', error);
            }
        }

        getIngredient()

        const destroy=(event)=>{
            Swal.fire({
                title: "Hapus Ingredient?",
                text: "Semua data terkait unit akan dihapus!",
                showCancelButton: true,
                cancelButtonColor: "#f46a6a",
                confirmButtonColor: "#34c38f",
                confirmButtonText: "Hapus!",
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteEndpoint = 'api/ingredient/destroy/'+event.id;

                    axios.delete(deleteEndpoint)
                        .then(response => {
                            Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            showConfirmButton: false, 
                            timer: 3000, 
                            iconColor: 'white',
                            customClass: {
                                popup: 'colored-toast',
                            },
                            timerProgressBar: true, 
                            icon: 'success', 
                            title: 'Ingredient berhasil dihapus.'
                            });
                            getIngredient()
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            });
            }

        return {
            ingredientData,
            destroy
        }
    },
    components: {
        Layout,
        PageHeader,
        lottie: Lottie,
        Slider,
        Multiselect,
        Swal,
        Head, Link
    },
};
</script>

<template>
  <Layout>

    <Head title="Ingredient" />
    <PageHeader title="Ingredient" pageTitle="Ingredient" />
    <BRow>
      <BCol xl="12" lg="12">
        <div>
          <BCard no-body>
            <BCardHeader class="border-0">
              <BRow class="g-4">
                <BCol sm="auto">
                  <div>
                    <Link :href="route('ingredient.create')" class="btn btn-success"><i class="ri-add-line align-bottom me-1"></i> Add
                    Ingredient</Link>
                  </div>
                </BCol>
                <BCol sm>
                  <div class="d-flex justify-content-sm-end">
                    <div class="search-box ms-2">
                      <input type="text" class="form-control" placeholder="Search Ingredient..." />
                      <i class="ri-search-line search-icon"></i>
                    </div>
                  </div>
                </BCol>
              </BRow>
            </BCardHeader>
            <BCardBody>
              <div class="tab-content text-muted">
                <div class="tab-pane active" id="productnav-all" role="tabpanel">
                  <div id="table-product-list-all" class="table-card gridjs-border-none table-responsive">
                    <table class="table align-middle table-nowrap mb-0 mt-5" id="customerTable">
                      <thead class="table-light">
                        <tr class="text-muted">
                          <th class="sort text-uppercase" data-sort="product">No</th>
                          <th class="sort text-uppercase" data-sort="stock">Name</th>
                          <th class="sort text-uppercase" data-sort="stock">code</th>
                          <th class="sort text-uppercase" data-sort="stock">category</th>
                          <th class="sort text-uppercase" data-sort="stock">Purchase Unit</th>
                          <th class="sort text-uppercase" data-sort="stock">consumtion unit</th>
                          <th class="sort text-uppercase" data-sort="stock">Conversion Rate</th>
                          <th class="sort text-uppercase" data-sort="stock">Purchase Price</th>
                          <th class="sort text-uppercase" data-sort="stock">Cost Per Unit</th>
                          <th class="sort text-uppercase" data-sort="stock">Min Stock</th>
                          <th class="sort text-uppercase" data-sort="stock">Stock</th>
                          <th class="sort text-uppercase" data-sort="stock">Added By</th>
                          <th scope="col">ACTION</th>
                        </tr>
                      </thead>
                      <tbody class="list form-check-all">
                        <tr class="gridjs-tr" v-for="(item,index) in ingredientData">
                          <td data-column-id="product" class="gridjs-td text-capitalize">
                           {{index+1}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.name}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.code}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.category.name}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.purchase_unit.name}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.consumtion_unit.name}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.conversion_rate}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.purchase_price}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.cost_per_unit}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.min_stock}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            {{item.stock}}
                          </td>
                          <td data-column-id="stock" class="gridjs-td text-capitalize">
                            <template v-if="item.user">
                              {{item.user.name}}
                            </template>
                          </td>
                          <td data-column-id="action" class="gridjs-td text-capitalize">
                            <span>
                              <div class="dropdown">
                                <BDropdown variant="link" toggle-class="btn btn-soft-secondary btn-sm dropdown arrow-none" menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }">
                                  <template #button-content> <i class="ri-more-fill"></i>
                                  </template>
                                  <Link class="dropdown-item" :href="route('ingredient.edit',item.id)"> <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                  Edit
                                  </Link>
                                  <li class="dropdown-divider"></li>
                                  <BDropdownItem @click="destroy(item)"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                    Delete
                                  </BDropdownItem>
                                </BDropdown>
                              </div>
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <BCol xxl="12" sm="12" class="project-card text-center" v-if="ingredientData.length==0">
                      <div class="p-5">
                        Tidak ada data ditemukan
                      </div>
                    </BCol>
                  </div>
                </div>
              </div>
            </BCardBody>
          </BCard>
        </div>
      </BCol>
    </BRow>
  </Layout>
</template>