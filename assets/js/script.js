const settingsBtn = document.getElementById('settingModalBtn')
const settingsModal = document.getElementById('settingsModal')

const orderItemTbl = document.getElementById('order-item-tbl')
const add_item = document.getElementById('add_item')

$(function(){
    $(settingsBtn).click(function(e){
        e.preventDefault()
        $(settingsModal).modal('show')
    })
    $(settingsModal).on('hidden.bs.modal', function(){
        $(settingsModal).find('form')[0].reset();
    })

    if(add_item != null){
        $(add_item).click(function(e){
            e.preventDefault()
            var item = $('#item').val()
            var unit = $('#unit').val()
            var qty = $('#qty').val()
            var price = $('#price').val()

            if(item == '' || unit == '' || qty == '' || price == '' || price < 1 || qty < 1)
                return false;
                qty = qty > 0 ? qty : 0;
                price = price > 0 ? price : 0;
                price = parseFloat(price)
            var total = parseFloat(qty) * parseFloat(price);
            
            var tr = $('<tr class="border-dark">')
            tr.append(`<td class="text-center border-dark"><button type="button" class="remove-item btn btn-sm btn-danger rounded-0 p-1"><i class="fas fa-times"></i></td>`)
            tr.append(`<td class="text-center border-dark">
            <input type="hidden" name="qty[]" value="${parseFloat(qty)}" />
            <input type="hidden" name="item[]" value="${item}" />
            <input type="hidden" name="unit[]" value="${unit}" />
            <input type="hidden" name="price[]" value="${price}" />
            <input type="hidden" name="total[]" value="${total}" />
            ${qty.toLocaleString('en-US')}
            </td>`)
            tr.append(`<td class="text-center border-dark">${unit}</td>`)
            tr.append(`<td class="border-dark">${item}</td>`)
            tr.append(`<td class="text-center border-dark">${price.toLocaleString('en-US', { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`)
            tr.append(`<td class="text-center border-dark">${total.toLocaleString('en-US', { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>`)

            $(orderItemTbl).append(tr)
            $(orderItemTbl).find('tbody tr.noData').hide()
            tr.find('.remove-item').click(function(e){
                e.preventDefault()
                if(confirm(`Are you sure to remove this item?`) === true){
                    tr.remove()
                    calc_total()
                    if($(orderItemTbl).find('tbody tr').length <= 1){
                        $(orderItemTbl).find('tbody tr.noData').show()
                    }
                }
            })
            $('#item').val('')
            $('#unit').val('pcs')
            $('#qty').val('1')
            $('#price').val('')
            calc_total()
        })
    }

    function calc_total(){
        var total = 0
        var discount_amount = 0
        var discounted_amount = 0
        var change = 0

        $(orderItemTbl).find('tbody tr').each(function(){
            if($(this).find('[name="total[]"]').length > 0)
                total = parseFloat(total) + parseFloat($(this).find('[name="total[]"]').val());
        })

        
        discount_amount = parseFloat(total) * (parseFloat($('[name="discount_percentage"]').val()) / 100)
        $('[name="discount_amount"]').val(discount_amount)

        discounted_amount = total - discount_amount;
        $('#grandTotalText').text(parseFloat(discounted_amount).toLocaleString('en-US', { style: 'decimal',minimumFractionDigits: 2, maximumFractionDigits: 2 }))

        change = parseFloat($('[name="tendered_amount"]').val()) - discounted_amount;

        $('#changeText').text(parseFloat(change).toLocaleString('en-US', { style: 'decimal',minimumFractionDigits: 2, maximumFractionDigits: 2 }))


        $('#subTotalText').text(parseFloat(total).toLocaleString('en-US', { style: 'decimal',minimumFractionDigits: 2, maximumFractionDigits: 2 }))
        $('[name="total_amount"]').val(parseFloat(total))

        $('#subTotalText').text(parseFloat(total).toLocaleString('en-US', { style: 'decimal',minimumFractionDigits: 2, maximumFractionDigits: 2 }))
        $('[name="total_amount"]').val(parseFloat(total))
    }
    $('[name="discount_percentage"], [name="tendered_amount"]').on('input paste', function(){
        calc_total()
    })
    $('#order-form-submit').click(function(e){
        e.preventDefault()
        if($('#order-form')[0].checkValidity() == true){
            if($(orderItemTbl).find('tbody tr [name="item[]"]').length <= 0){
                alert("Please add at least 1 Item first!");
            }else{
                $('#order-form').submit();
            }
        }else{
            $('#order-form')[0].reportValidity();
        }
    })
})
