
<aside class="main-sidebar sidebar-dark-warning elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{asset('assets/admin/imgs/logo.png')}}" alt="Bact Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">BactEducation</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('assets/admin/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
         

               <li class="nav-item has-treeview {{ (request()->is('admin/adminpanelsetting*') || request()->is('admin/treasuries*'))? 'menu-open':'' }} ">
                <a href="#" class="nav-link  {{ (request()->is('admin/adminpanelsetting*') || request()->is('admin/treasuries*'))? 'active':'' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Settings
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                
                  <li class="nav-item"> <a href="{{route('admin.adminpanelsetting.index')}}" class="nav-link {{ (request()->is('admin/adminpanelsetting*'))? 'active':'' }}"><i class="nav-icon fas fa-th"></i> <p> Settings</p></a> </li>

                  <li class="nav-item"><a href="{{route('admin.treasuries.index')}}" class="nav-link {{ (request()->is('admin/treasuries*'))? 'active':'' }}"><i class="nav-icon fas fa-th"></i><p>Treasuries</p> </a></li>

                </ul>
              </li>


              <li class="nav-item has-treeview {{ (request()->is('admin/sales_material_types*') || request()->is('admin/stores*') || request()->is('admin/uoms*') || request()->is('admin/inv_itemcard_categories*') || request()->is('admin/inv_itemcard/*') )? 'menu-open':'' }} ">
                <a href="#" class="nav-link  {{ (request()->is('admin/sales_material_types*') || request()->is('admin/stores*') || request()->is('admin/uoms*') || request()->is('admin/inv_itemcard_categories*') || request()->is('admin/inv_itemcard/*') )? 'active':'' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Branchs Settings
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                
                  <li class="nav-item"><a href="{{route('admin.sales_material_types.index')}}" class="nav-link {{ (request()->is('admin/sales_material_types*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p> Billing categories data</p></a></li>

                  <li class="nav-item"><a href="{{route('admin.stores.index')}}" class="nav-link {{ (request()->is('admin/stores*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p>Branchs Data</p> </a></li>

                  <li class="nav-item"><a href="{{route('admin.uoms.index')}}" class="nav-link {{ (request()->is('admin/uoms*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p>Measurement Units</p></a></li>
 
                  <li class="nav-item"><a href="{{route('inv_itemcard_categories.index')}}" class="nav-link {{ (request()->is('admin/inv_itemcard_categories*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p>ItemCategories</p></a></li>

                  <li class="nav-item"><a href="{{route('admin.inv_itemcard.index')}}" class="nav-link {{ (request()->is('admin/inv_itemcard/*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p>Categories</p></a></li>

                
                </ul>
              </li>


              <li class="nav-item has-treeview {{ (request()->is('admin/accounttypes*') || request()->is('admin/accounts*') || request()->is('admin/customers*') || request()->is('admin/suppliers_categories*') || request()->is('admin/suppliers/*') || request()->is('admin/collect_transaction*') || request()->is('admin/exchange_transaction*'))? 'menu-open':''}}">
                <a href="#" class="nav-link {{ (request()->is('admin/accounttypes*') || request()->is('admin/accounts*') || request()->is('admin/customers*') || request()->is('admin/suppliers_categories*') || request()->is('admin/suppliers/*') || request()->is('admin/collect_transaction*') || request()->is('admin/exchange_transaction*') )? 'active':''}}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Accounting
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                
                  <li class="nav-item"> <a href="{{route('admin.accounttypes.index')}}" class="nav-link {{(request()->is('admin/accounttypes*'))? 'active':''}}"><i class="nav-icon fas fa-th"></i> <p> AccountTypes</p></a> </li>
                  
                  <li class="nav-item"> <a href="{{route('admin.accounts.index')}}" class="nav-link {{(request()->is('admin/accounts*'))? 'active':''}}"><i class="nav-icon fas fa-th"></i> <p> Accounts</p></a> </li>

                  <li class="nav-item"> <a href="{{route('admin.customers.index')}}" class="nav-link {{(request()->is('admin/customers*'))? 'active':''}}"><i class="nav-icon fas fa-th"></i> <p> CustomersAccounts</p></a> </li>
 
                  <li class="nav-item"> <a href="{{route('admin.suppliers_categories.index')}}" class="nav-link {{(request()->is('admin/suppliers_categories*'))? 'active':''}}"><i class="nav-icon fas fa-th"></i> <p> SuppliersCategories</p></a> </li>

                  
                  <li class="nav-item"> <a href="{{route('admin.suppliers.index')}}" class="nav-link {{(request()->is('admin/suppliers/*'))? 'active':''}}"><i class="nav-icon fas fa-th"></i> <p> SuppliersAccounts</p></a> </li>
 
                  <li class="nav-item"> <a href="{{route('admin.collect_transaction.index')}}" class="nav-link {{(request()->is('admin/collect_transaction*'))? 'active':''}}"><i class="nav-icon fas fa-th"></i> <p> CollectionScreen</p></a> </li>

                  <li class="nav-item"> <a href="{{route('admin.exchange_transaction.index')}}" class="nav-link {{(request()->is('admin/exchange_transaction*'))? 'active':''}}"><i class="nav-icon fas fa-th"></i> <p>ExchangeScreen</p></a> </li>

                </ul>
              </li>
        
           
              <li class="nav-item has-treeview {{ ( request()->is('admin/supplierswithorders*') )? 'menu-open':'' }} ">
                <a href="#" class="nav-link  {{ (request()->is('admin/supplierswithorders*'))? 'active':'' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Transactions
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                
                  <li class="nav-item"><a href="{{route('admin.supplierswithorders.index')}}" class="nav-link {{ (request()->is('admin/supplierswithorders*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p> Purchase invoices</p></a></li>

                
                </ul>
              </li>


              <li class="nav-item has-treeview {{ ( request()->is('admin/admins_accounts*') )? 'menu-open':'' }} ">
                <a href="#" class="nav-link  {{ (request()->is('admin/admins_accounts*'))? 'active':'' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Permissions
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                
                  <li class="nav-item"><a href="{{route('admin.admins_accounts.index')}}" class="nav-link {{ (request()->is('admin/admins_accounts*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p>Users</p></a></li>

                
                </ul>
              </li>


              <li class="nav-item has-treeview {{ ( request()->is('admin/admin_shift*') )? 'menu-open':'' }} ">
                <a href="#" class="nav-link  {{ (request()->is('admin/admin_shift*'))? 'active':'' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    TreasuryShiftTransactions
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                
                  <li class="nav-item"><a href="{{route('admin.admin_shift.index')}}" class="nav-link {{ (request()->is('admin/admin_shift*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p>TreasuryShifts</p></a></li>

                
                </ul>
              </li>


              <li class="nav-item has-treeview {{ ( request()->is('admin/sales_invoices*') )? 'menu-open':'' }} ">
                <a href="#" class="nav-link  {{ (request()->is('admin/sales_invoices*'))? 'active':'' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Sales
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                
                  <li class="nav-item"><a href="{{route('admin.sales_invoices.index')}}" class="nav-link {{ (request()->is('admin/sales_invoices*')) ? 'active':''}}"><i class="nav-icon fas fa-th"></i><p>SalesInvoices</p></a></li>

                
                </ul>
              </li>


        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>