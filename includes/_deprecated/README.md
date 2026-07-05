# Deprecated files

These files declared duplicate classes (`VSL_Query`, `VSL_Renderer`, `VSL_Shortcode`)
with older, incompatible signatures. They are kept here for reference only and
must NOT be require'd anywhere in the plugin. The canonical versions live in
`includes/core/`.

## app-core-unused/

`app/Core/{Application,Kernel,Container,QueryService}.php` and
`bootstrap/kernel.php` were dead code: never require'd from anywhere in the
plugin, and `bootstrap/kernel.php` itself called a static `Application::boot()`
method that didn't even exist on the class (would have fatal-errored if it
had ever been executed). Superseded by the working Provider architecture in
`app/Providers/` (see ProviderEngine/ProviderRegistry/ProviderPipeline).
