<script>
import { Link, router } from '@inertiajs/vue3';
import { layoutComputed } from "@/state/helpers";
import simplebar from "simplebar-vue";

export default {
  components: {
    simplebar,
    Link
  },
  data() {
    return {
      settings: {
        minScrollbarLength: 60,
      },
    };
  },
  computed: {
    ...layoutComputed,
    layoutType: {
      get() {
        return this.$store ? this.$store.state.layout.layoutType : {} || {};
      },
    },
  },
  mounted() {
    this.initActiveMenu();
    this.onRoutechange();
    if (document.querySelectorAll(".navbar-nav .collapse")) {
      let collapses = document.querySelectorAll(".navbar-nav .collapse");

      collapses.forEach((collapse) => {
        // Hide sibling collapses on `show.bs.collapse`
        collapse.addEventListener("show.bs.collapse", (e) => {
          e.stopPropagation();
          let closestCollapse = collapse.parentElement.closest(".collapse");
          if (closestCollapse) {
            let siblingCollapses =
              closestCollapse.querySelectorAll(".collapse");
            siblingCollapses.forEach((siblingCollapse) => {
              if (siblingCollapse.classList.contains("show")) {
                siblingCollapse.classList.remove("show");
                siblingCollapse.parentElement.firstChild.setAttribute("aria-expanded", "false");
              }
            });
          } else {
            let getSiblings = (elem) => {
              // Setup siblings array and get the first sibling
              let siblings = [];
              let sibling = elem.parentNode.firstChild;
              // Loop through each sibling and push to the array
              while (sibling) {
                if (sibling.nodeType === 1 && sibling !== elem) {
                  siblings.push(sibling);
                }
                sibling = sibling.nextSibling;
              }
              return siblings;
            };
            let siblings = getSiblings(collapse.parentElement);
            siblings.forEach((item) => {
              if (item.childNodes.length > 2) {
                item.firstElementChild.setAttribute("aria-expanded", "false");
                item.firstElementChild.classList.remove("active");
              }
              let ids = item.querySelectorAll("*[id]");
              ids.forEach((item1) => {
                item1.classList.remove("show");
                item1.parentElement.firstChild.setAttribute("aria-expanded", "false");
                item1.parentElement.firstChild.classList.remove("active");
                if (item1.childNodes.length > 2) {
                  let val = item1.querySelectorAll("ul li a");

                  val.forEach((subitem) => {
                    if (subitem.hasAttribute("aria-expanded"))
                      subitem.setAttribute("aria-expanded", "false");
                  });
                }
              });
            });
          }
        });

        // Hide nested collapses on `hide.bs.collapse`
        collapse.addEventListener("hide.bs.collapse", (e) => {
          e.stopPropagation();
          let childCollapses = collapse.querySelectorAll(".collapse");
          childCollapses.forEach((childCollapse) => {
            let childCollapseInstance = childCollapse;
            childCollapseInstance.classList.remove("show");
            childCollapseInstance.parentElement.firstChild.setAttribute("aria-expanded", "false");
          });
        });
      });
    }
  },

  methods: {
    onRoutechange() {
      // this.initActiveMenu();
      setTimeout(() => {
        var currentPath = window.location.pathname;
        if (document.querySelector("#navbar-nav")) {
          let currentPosition = document.querySelector("#navbar-nav").querySelector('[href="' + currentPath + '"]')?.offsetTop;
          if (currentPosition > document.documentElement.clientHeight) {
            document.querySelector("#scrollbar .simplebar-content-wrapper") ? document.querySelector("#scrollbar .simplebar-content-wrapper").scrollTop = currentPosition + document.documentElement.clientHeight / 2 : '';
          }
        }
      }, 500);
    },

    initActiveMenu() {
      setTimeout(() => {
        var currentPath = window.location.pathname;
        if (document.querySelector("#navbar-nav")) {
          let a = document.querySelector("#navbar-nav").querySelector('[href="' + currentPath + '"]');
          if (a) {
            a.classList.add("active");
            let parentCollapseDiv = a.closest(".collapse.menu-dropdown");
            if (parentCollapseDiv) {
              parentCollapseDiv.classList.add("show");
              parentCollapseDiv.parentElement.children[0].classList.add("active");
              parentCollapseDiv.parentElement.children[0].setAttribute("aria-expanded", "true");
              if (parentCollapseDiv.parentElement.closest(".collapse.menu-dropdown")) {
                parentCollapseDiv.parentElement.closest(".collapse").classList.add("show");
                if (parentCollapseDiv.parentElement.closest(".collapse").previousElementSibling)
                  parentCollapseDiv.parentElement.closest(".collapse").previousElementSibling.classList.add("active");
                const grandparent = parentCollapseDiv.parentElement.closest(".collapse").previousElementSibling.parentElement.closest(".collapse");
                if (grandparent && grandparent && grandparent.previousElementSibling) {
                  grandparent.previousElementSibling.classList.add("active");
                  grandparent.classList.add("show");
                }
              }
            }
          }
        }
      }, 0);
    },
  },
};
</script>

<template>
  <BContainer fluid>
    <div id="two-column-menu"></div>

    <template v-if="layoutType === 'vertical' || layoutType === 'semibox'">
      <ul class="navbar-nav h-100" id="navbar-nav">
        <!-- <li class="menu-title">
          <span data-key="t-menu"> {{ $t("t-menu") }}</span>
        </li>
        <li class="nav-item">
          <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
            <i class="ri-dashboard-2-line"></i>
            <span data-key="t-dashboards"> {{ $t("t-dashboards") }}</span>
          </a>
          <div class="collapse menu-dropdown" id="sidebarDashboards">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <Link href="/dashboard/analytics" class="nav-link custom-abc" data-key="t-analytics">
                {{ $t("t-analytics") }}
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/dashboard/crm" class="nav-link" data-key="t-crm">
                {{ $t("t-crm") }}
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/" class="nav-link" data-key="t-ecommerce">
                {{ $t("t-ecommerce") }}
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/dashboard/crypto" class="nav-link" data-key="t-crypto">
                {{ $t("t-crypto") }}
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/dashboard/projects" class="nav-link" data-key="t-projects">
                {{ $t("t-projects") }}
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/dashboard/nft" class="nav-link" data-key="t-nft">
                {{ $t("t-nft") }}
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/dashboard/job" class="nav-link" data-key="t-job">
                {{ $t("t-job") }}
                </Link>
              </li>
            </ul>
          </div>
        </li> -->
        <li class="nav-item">
          <Link class="nav-link menu-link" href="/dashboard">
            <i class="ri-dashboard-2-line"></i>
          <span data-key="t-widgets">Dashboard</span>
          </Link>
        </li>
        <li class="nav-item">
          <a class="nav-link menu-link" href="#outlet" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="outlet">
            <i class="bx bx-store"></i>
            <span data-key="t-dashboards">Outlet</span>
          </a>
          <div class="collapse menu-dropdown" id="outlet">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <Link href="/outlet" class="nav-link custom-abc" data-key="t-analytics">
                List Outlet
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/outlet/create" class="nav-link" data-key="t-crm">
                Add Outlet
                </Link>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link menu-link" href="#ingredient" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="outlet">
            <i class="bx bx-package"></i>
            <span data-key="t-dashboards">Ingredient</span>
          </a>
          <div class="collapse menu-dropdown" id="ingredient">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <Link href="/ingredient_unit/create" class="nav-link" data-key="t-crm">
                Add Ingredient Unit
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/ingredient_unit" class="nav-link custom-abc" data-key="t-analytics">
                List Ingredient Unit
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/ingredient_category/create" class="nav-link" data-key="t-crm">
                Add Ingredient Category
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/ingredient_category" class="nav-link custom-abc" data-key="t-analytics">
                List Ingredient Category
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/ingredient/create" class="nav-link" data-key="t-crm">
                Add Ingredient
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/ingredient" class="nav-link custom-abc" data-key="t-analytics">
                List Ingredient
                </Link>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link menu-link" href="#menu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="outlet">
            <i class="bx bx-bowl-rice"></i>
            <span data-key="t-dashboards">Menu</span>
          </a>
          <div class="collapse menu-dropdown" id="menu">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <Link href="/outlet/create" class="nav-link" data-key="t-crm">
                Add Food Menu Category
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/outlet" class="nav-link custom-abc" data-key="t-analytics">
                List Food Menu Category
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/outlet/create" class="nav-link" data-key="t-crm">
                Add Food Menu
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/outlet" class="nav-link custom-abc" data-key="t-analytics">
                List Food Menu
                </Link>
              </li>
            </ul>
          </div>
        </li>
        <li class="menu-title">
          <span data-key="t-menu"> Setting</span>
        </li>
        <li class="nav-item">
          <a class="nav-link menu-link" href="#accountAndUser" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="accountAndUser">
            <i class="bx bx-user"></i>
            <span data-key="t-dashboards"> Akun dan User</span>
          </a>
          <div class="collapse menu-dropdown" id="accountAndUser">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <Link href="/user" class="nav-link custom-abc" data-key="t-analytics">
                List User
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/user/create" class="nav-link" data-key="t-crm">
                Add User
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/" class="nav-link" data-key="t-ecommerce">
                Change Profile
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/dashboard/crypto" class="nav-link" data-key="t-crypto">
                Change Password
                </Link>
              </li>
            </ul>
          </div>
        </li>
        <!-- end Dashboard Menu -->
      </ul>
    </template>
  </BContainer>
</template>