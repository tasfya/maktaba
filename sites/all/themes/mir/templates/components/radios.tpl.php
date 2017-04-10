<script type="text/html" id="radios_tmpl">
  <div role="tabpanel" class="col-sm-9 tabbable-line">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
    <% for ( var i = 0; i < radios.length; i++ ) { %>
      <li role="presentation" class="<%= active_if_0(i) %>">
        <a href="#tab-<%= i %>" aria-controls="home" role="tab" data-toggle="tab"><%=radios[i].name%></a>
      </li>
    <% } %>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <% for ( var i = 0; i < radios.length; i++ ) { %>
      <div role="tabpanel" class="tab-pane <%= active_if_0(i) %>" id="tab-<%=i%>">
        <div class="radio-wrapper">
          <h2><%= radios[i].name %></h2>
          <p><%= radios[i].description %></p>
          <div>
            <img src="http://192.34.56.9/<%=radios[i].logo%>" width="400">
          </div>
          <div class="streaming-info col-sm-6">
            <table class="table table-condensed table-hover">
              <thead>
                <tr>
                  <th>يبث الآن</th>
                  <th>الاستماع</th>
                  <th>المستمعين</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><%= radios[i].live_info.current_playing %></td>
                  <td>
                    <div class="radio-streaming-player-wrapper">
                      <a class="play" href="<%= radios[i].streaming_url %>">
                        <i class="fa fa-play"></i>
                      </a>
                    </div>
                  </td>
                  <td><%= radios[i].live_info.listeners_count %></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <% } %>
    </div>
  </div>
</script>

<div id="radios"></div>
