@props([
    'title',
    'value' => 0,
    'icon',
    'timelineLabel',
    'colorVar' => 'primary',
    'cancelledAmount' => null
])

<div class="col-md-4">
  <div class="card glass-card-hover card-c p-0 h-100 border-0 border-start border-4" style="border-color: var(--{{ $colorVar }}) !important;">
    <div class="card-body d-flex flex-column justify-content-between">
      <div class="d-flex align-items-center justify-content-between w-100 mb-2">
        <div>
          <h6 class="text-uppercase fw-bold p-0 m-0" style="color: var(--{{ $colorVar }})">
            {{ $title }}
          </h6>
          <p class="mb-0">
            <small><i>{{ $timelineLabel }}</i></small>
          </p>
        </div>
        <div class="fs-1 opacity-60" style="color: var(--{{ $colorVar }});">
          <i class="bi {{ $icon }}"></i>
        </div>  
      </div>
      
      <div>
        <h2 class="fw-bold fs-2 m-0 mb-1" style="color: var(--{{ $colorVar }})">
          ₱{{ number_format($value, 2) }}
        </h2>
                
        @if($cancelledAmount !== null && $cancelledAmount > 0)
        <small class="text-uppercase fw-bold d-block" style="font-size: 0.78rem; color: #ff0000c0">
          Total Amount Cancelled: ₱{{ number_format($cancelledAmount, 2) }}
        </small>
        @else
        <small class="fw-bold d-block" style="font-size: 0.78rem; color: #ff000000">-</small>
        @endif
      </div>
    </div>
  </div>
</div>