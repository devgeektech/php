@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')
        @if($img_link != "")
        <tr>
            <td class="paragraph">
                <img src="{{$img_link}}">
            </td>
        </tr>
        @endif
        <tr>
            <td class="title">
                {{$title}}
            </td>
        </tr>
        <tr>
            <td width="100%" height="10"></td>
        </tr>
        <tr>
            <td class="paragraph" colspan="2">
                {!! $content !!}
            </td>
        </tr>
        @if(isset($table_data) && $table_data != null && $table_data != "")
        <tr>
            <td>

            <table width="300" style="line-height: 25px; text-indent: 5px">
                <tr>
                    <td>Name:</td><td>{!! $table_data['name'] !!}</td>
                </tr>
                <tr bgcolor="#f2f2f2">
                    <td>Phone:</td><td>{!! $table_data['phone'] !!}</td>
                </tr>
                <tr>
                    <td>Email:</td><td>{!! $table_data['email'] !!}</td>
                </tr>
                <tr  bgcolor="#f2f2f2">
                    <td>Guests:</td><td>{!! $table_data['guests'] !!}</td>
                </tr>
            </td>
            </table>
        </tr>
        @endif
        <!-- <tr>
            <td width="100%" height="25"></td>
        </tr> -->
        @if($link_activation != "")
        <tr>
            <td>
                @include('beautymail::templates.minty.button', ['text' => $text_on_link, 'link' => $link_activation])
            </td>
        </tr>
        @endif
        @if(isset($footer) && $footer != "")
        <tr>
            <td width="100%" height="25"></td>
        </tr>
        <tr>
            <td class="paragraph">
                <strong>{!! $footer !!}</strong>
            </td>
        </tr>
        @endif
        <tr>
            <td width="100%" height="25"></td>
        </tr>
    @include('beautymail::templates.minty.contentEnd')
@stop