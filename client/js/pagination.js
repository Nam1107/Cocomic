function pagination(curPages,totalPages){
    const delta = 1;
    var pageLeft = curPages - delta;
    var pageRight = curPages + delta;
    if(pageLeft<=2+delta){
        pageLeft = 1;
    }
    if(pageRight>=totalPages-delta-1){
        pageRight = totalPages;
    }

    if(pageLeft>delta){
        $('#pagination').append(
            `
            <input onclick="LoadPage(1)" class="item-page"  type="button"  value="1" ></input>
            <input  class="item-page"  type="button"  value="..." ></input>
            `
          );
    }
    for(var i=pageLeft;i<=pageRight;i++){
        if(i==curPages){
          $('#pagination').append(
            `
          <input  class="item-page-active"  type="button" value="${i}" ></input>
          `
        );
          continue;
        }
        $('#pagination').append(
          `
          <input onclick="LoadPage(${i})" class="item-page"  type="button"  value="${i}" ></input>
          `
        );
    }
    if(pageRight<=totalPages-delta){
        $('#pagination').append(
            `
            <input  class="item-page"  type="button"  value="..." ></input>
            <input onclick="LoadPage(${totalPages})" class="item-page"  type="button"  value="${totalPages}" ></input>
            `
          );
    }
}