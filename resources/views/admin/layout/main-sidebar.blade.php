<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">INVENTORY SYSTEM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{session('admin_session')->fullname}}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                  <a href="/admin" class="nav-link">
                  <i class="fab fa-bandcamp"></i>
                    <p>Home</p>
                  </a>
                </li>
                <li class="nav-item">
                <a href="{{route('admin.delivery.list')}}" class="nav-link {{current_nav('delivery')}}">
                <i class="fas fa-warehouse"></i>
                  <p>Deliveries</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.product.listing')}}" class="nav-link {{current_nav('product')}}">
                <i class="fab fa-product-hunt"></i>
                  <p>Products</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{route('admin.brand.listing')}}" class="nav-link {{current_nav('brand')}}">
                <i class="fab fa-bandcamp"></i>
                  <p>Brands</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.category.listing')}}" class="nav-link {{current_nav('category')}}">
                <i class="fas fa-tags"></i>
                  <p>Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.uom.listing')}}" class="nav-link {{current_nav('unit-of-measure')}}">
                <i class="fas fa-ruler-horizontal"></i>
                  <p>Unit Of Measures</p>
                </a>
              </li>
              
              
          <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="fas fa-shopping-cart"></i>
              <p>
                Orders
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.order.new')}}" class="nav-link {{current_nav('new-order')}}">
                <i class="fas fa-cart-plus"></i>
                  <p>Create New Order</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.order.listing')}}" class="nav-link {{current_nav('paid')}}">
                <i class="fas fa-hand-holding-usd"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
                <a href="{{route('admin.supplier.listing')}}" class="nav-link {{current_nav('suppliers')}}">
                <i class="fas fa-warehouse"></i>
                  <p>Suppliers</p>
                </a>
              </li>
          <li class="nav-item">
              <a href="{{route('admin.user.listing')}}" class="nav-link {{current_nav('user')}}">
              <i class="fas fa-users"></i>
                <p>Manage Users</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="{{route('admin.transaction.histories.listing')}}" class="nav-link {{current_nav('transaction-history')}}">
              <i class="fas fa-history"></i>
                <p>Transaction History</p>
              </a>
          </li>
          
          <li class="nav-item">
            <a href="{{route('admin.get.logout')}}" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </li>

        </ul>
        
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>