<script>
    import { Link } from 'svelte-routing'
  </script>

<h1>Test Page</h1>
<p>It works!</p>
<div class="actions">
    <div class="action">
      <!-- svelte-ignore a11y-click-events-have-key-events a11y-no-static-element-interactions a11y-missing-attribute-->
      <Link to="/">Back to home!</Link>
    </div>
  </div>