@extends("layouts.master")

@section("title")
  html2canvas docs
@endsection

@section("content")
  <div class="container" ng-view=""><div class="row-fluid">
    <ol class="breadcrumb">
    <li><a href="{{route("front")}}">Frontend</a></li>
    <li>
      <a href="{{url("frontend/html2canvas/base")}}">
        Html to canvas base php
      </a>
    </li>
    <li>docs</li>
  </ol>
  <div class="span3">
    <ul class="nav nav-tabs nav-stacked" id="doc-nav"><li><a href="#introduction">Introduction</a></li><li><a href="#how-it-works">How it works</a></li><li><a href="#limitations">Limitations</a></li><li><a href="#getting-started">Getting started</a></li><li><a href="#available-options">Available options</a></li></ul>
  </div>
  <div class="span9">
    <div class="row-fluid" id="docs">
      <div class="page-header">
        <h1 id="introduction">Introduction</h1>
      </div>
      <p class="lead">
        Before you get started with the script, there are a few things that are good to know regarding the script and some of its limitations.
      </p>

      <h3 id="how-it-works">How it works</h3>
      <p>
        The script traverses through the DOM of the page it is loaded on. It gathers information on all the elements there, which it then uses to build a representation of the page.
        In other words, it does not actually take a screenshot of the page, but builds a representation of it based on the properties it reads from the DOM.
      </p>
      <p>As a result, it is only able to render correctly properties that it understands, meaning there are many CSS properties which do not work.</p>


      <h3 id="limitations">Limitations</h3>
      <p>
        All the images that the script uses need to reside under the <a href="http://en.wikipedia.org/wiki/Same_origin_policy">same origin</a> for it to be able to read them without the assistance of a <a href="#">proxy</a>.
        Similarly, if you have other <code>canvas</code> elements on the page, which have been tainted with cross-origin content, they will become dirty and no longer readable by html2canvas.
      </p>

      <p>
        The script doesn't render plugin content such as Flash or Java applets. It doesn't render <code>iframe</code> content either.
      </p>

      <div class="page-header">
        <h1 id="getting-started">Getting started</h1>
      </div>

      <p>To run html2canvas on an <code>element</code> with some <code>options</code> simply call:</p>

      <pre class="prettyprint linenums">html2canvas(element, options);</pre>

      <p>The rendered <code>canvas</code> is provided in the callback event <code>onrendered</code>, as such:</p>

      <pre class="prettyprint linenums">html2canvas(element, {
    onrendered: function(canvas) {
        // canvas is the final rendered &lt;canvas&gt; element
    }
});</pre>

      <h3 id="available-options">Available options</h3>

      <p>The options variable is an <code>object</code> which accepts the following parameters:</p>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Default</th>
            <th>Description</th>

          </tr>
        </thead>
        <tbody>

          <tr>
            <td>allowTaint</td>
            <td>boolean</td>
            <td>false</td>
            <td>Whether to allow cross-origin images to taint the canvas</td>
          </tr>

          <tr>
            <td>background</td>
            <td>string</td>
            <td>#fff</td>
            <td>Canvas background color, if none is specified in DOM. Set undefined for transparent</td>
          </tr>

          <tr>
            <td>height</td>
            <td>number</td>
            <td>null</td>
            <td>Define the height of the canvas in pixels. If null, renders with full height of the window.</td>
          </tr>

          <tr>
            <td>letterRendering</td>
            <td>boolean</td>
            <td>false</td>
            <td>Whether to render each letter separately. Necessary if <code>letter-spacing</code> is used.</td>
          </tr>

          <tr>
            <td>logging</td>
            <td>boolean</td>
            <td>false</td>
            <td>Whether to log events in the console.</td>
          </tr>

          <tr>
            <td>proxy</td>
            <td>string</td>
            <td>undefined</td>
            <td>Url to the proxy which is to be used for loading cross-origin images. If left empty, cross-origin images won't be loaded.</td>
          </tr>

          <tr>
            <td>taintTest</td>
            <td>boolean</td>
            <td>true</td>
            <td>Whether to test each image if it taints the canvas before drawing them</td>
          </tr>

          <tr>
            <td>timeout</td>
            <td>number</td>
            <td>0</td>
            <td>Timeout for loading images, in milliseconds. Setting it to 0 will result in no timeout. </td>
          </tr>

          <tr>
            <td>width</td>
            <td>number</td>
            <td>null</td>
            <td>Define the width of the canvas in pixels. If null, renders with full width of the window.</td>
          </tr>

          <tr>
            <td>useCORS</td>
            <td>boolean</td>
            <td>false</td>
            <td>Whether to attempt to load cross-origin images as CORS served, before reverting back to proxy</td>
          </tr>

        </tbody>
      </table>

    </div>

  </div>
</div>


</div>
@endsection
