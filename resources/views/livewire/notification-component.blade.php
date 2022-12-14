  <div wire:poll.visible.10000ms class="row">
      <!-- Nav Item - Alerts -->
      <li class="nav-item dropdown no-arrow mx-1">
          <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-bell fa-fw"></i>
              <!-- Counter - Alerts -->
              <span class="badge badge-danger badge-counter">{{ count($notification) }}</span>
          </a>
          <!-- Dropdown - Alerts -->
          <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
              aria-labelledby="alertsDropdown">
              <h6 class="dropdown-header">
                  Requests to Approve
              </h6>
              @foreach ($notification as $notify)
                  <a class="dropdown-item d-flex align-items-center" href="#">
                      <div class="mr-3">
                          <div class="icon-circle bg-primary">
                              <i class="fas fa-file-alt text-white"></i>
                          </div>
                      </div>
                      <div>
                          <div class="small text-gray-500">{{ date('F d, Y', strtotime($notify->created_at)) }}</div>
                          <span
                              class="font-weight-bold">{{ $notify->requisition->requistion_number . ' ' . $notify->requisition->notes }}</span>
                      </div>
                  </a>
              @endforeach
              <a class="dropdown-item text-center small text-gray-500" href="{{ route('requisitions-approval') }}">View
                  All</a>
          </div>
      </li>
  </div>
